<template>
    <div>
        <div class="is-flex is-justify-content-center mb-2"
            style="font-size: 20px; font-weight: bold;">LIST OF ARCHIVE EVENTS</div>
            <hr>
            <div class="level">
                <div class="level-left">
                    <b-field label="Page">
                        <b-select v-model="perPage" @input="setPerPage">
                            <option value="10">10 per page</option>
                            <option value="20">20 per page</option>
                            <option value="30">30 per page</option>
                            <option value="40">40 per page</option>
                        </b-select>
                        <b-select v-model="sortOrder" @input="loadAsyncData">
                            <option value="asc">ASC</option>
                            <option value="desc">DESC</option>

                        </b-select>
                    </b-field>
                </div>

                <div class="level-right">
                    <div class="level-item">
                        <b-field label="Search">
                            <b-input type="text"
                                v-model="search.event" placeholder="Search Event"
                                @keyup.native.enter="loadAsyncData"/>
                            <p class="control">
                                <b-tooltip label="Search" type="is-success">
                                    <b-button type="is-primary" icon-right="magnify" @click="loadAsyncData"/>
                                </b-tooltip>
                            </p>
                        </b-field>
                    </div>
                </div>
            </div>

            <hr>

            <div class="buttons" v-if="['ADMINISTRATOR'].includes(propUser.role)">

                <b-button
                    label="Clear checked"
                    type="is-danger"
                    icon-left="close"
                    size="is-small"
                    class="field"
                    @click="checkedRows = []" />
                
                <b-button
                    label="Return to Main List"
                    type="is-info"
                    icon-left="arrow-left"
                    size="is-small"
                    class="field"
                    @click="undoArchive" />
            </div>


            <b-table
                :data="data"
                :loading="loading"
                paginated
                detailed
                checkable
                :checked-rows.sync="checkedRows"
                backend-pagination
                :total="total"
                :per-page="perPage"
                @page-change="onPageChange"
                aria-next-label="Next page"
                aria-previous-label="Previous page"
                aria-page-label="Page"
                aria-current-label="Current page"
                backend-sorting
                :default-sort-direction="defaultSortDirection"
                @sort="onSort">

                <!-- <b-table-column field="event_id" label="ID" v-slot="props">
                    {{ props.row.event_id }}
                </b-table-column> -->

                <b-table-column field="academic_year" label="AY" v-slot="props">
                    {{ props.row.academic_year.academic_year_code }}
                </b-table-column>

                <b-table-column field="event" label="Event" v-slot="props">
                    {{ props.row.event }}
                </b-table-column>

                <b-table-column field="event_type" label="Type" v-slot="props">
                    {{ props.row.event_type.event_type }}
                </b-table-column>

              
                <b-table-column field="event_date" label="Event Date" v-slot="props">
                    {{ new Date(props.row.event_date_from).toLocaleDateString() }}
                    -
                    {{ new Date(props.row.event_date_to).toLocaleDateString() }}
      
                </b-table-column>

                <b-table-column field="time" label="Time" v-slot="props">
                    {{ props.row.event_time_from | formatTime }} - 
                    {{ props.row.event_time_to | formatTime }}
                </b-table-column>

                <!-- <b-table-column field="duration" label="Duration" v-slot="props">
                    {{ durationHours(
                        new Date(props.row.event_date + ' ' + props.row.event_time_from),
                        new Date(props.row.event_date + ' ' + props.row.event_time_to)
                    ) }}
                    
                </b-table-column> -->

                <b-table-column field="approval_status" label="Status" v-slot="props">
                    <span v-if="props.row.approval_status === 1" class="yes">APPROVED</span>
                    <span v-else-if="props.row.approval_status === 0" class="pending">PENDING</span>
                    <span v-else-if="props.row.approval_status === 2" class="no">DECLINED</span>
                </b-table-column>

<!-- 
                <b-table-column field="is_open" label="Evaluation" v-slot="props">
                    <span v-if="props.row.is_open === 1" class="yes">OPEN</span>
                    <span v-else class="no">CLOSE</span>
                </b-table-column> -->

                

                <template #detail="props">
                    <tr>
                        <th>Description</th>
                        <th>Venue</th>
                        <th>Need Approval</th>
                        <th>Approve Officer</th>
                    </tr>
                    <tr>
                        <td>
                             <span v-if="props.row.event_content">
                                {{ props.row.event_content | truncate(50) }}
                            </span>

                        </td>
                        <td>
                            <span v-if="props.row.venue">{{ props.row.venue.event_venue }}</span>
                        </td>
                        <td>
                            <span v-if="props.row.is_need_approval === 1" class="yes">YES</span>
                            <span v-else-if="props.row.is_need_approval === 0" class="pending">NO</span>
                        </td>
                        <td>
                            <span v-if="props.row.approving_officer">
                                {{ props.row.approving_officer.lname }}, {{ props.row.approving_officer.fname }} {{ props.row.approving_officer.mname }}
                            </span>
                        </td>
                    </tr>
                </template>
            
            </b-table>

            <!-- <hr>

            <div class="buttons mt-3" v-if="['ORGANIZER'].includes(propUser.role)">
                <b-button tag="a"
                    href="/events/create"
                    icon-right="calendar" class="is-primary is-outlined">NEW</b-button>
            </div> -->

    </div>
</template>

<script>

export default{

    props: {
        propUser:{
            type: Object,
            default: {}
        },
    },
   

    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'event_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 20,
            defaultSortDirection: 'asc',


            search: {
                event: '',
            },


            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },


            checkedRows: [],

        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `event=${this.search.event}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-archive-events?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },


        

        durationHours(stime, etime){
            let timeDifference =  etime - stime;
            // Convert milliseconds to hours
            let hoursDifference = timeDifference / (1000 * 60 * 60);
            let result = parseFloat(hoursDifference.toFixed(2))
            return result + 'hour(s)'
        },

        undoArchive(){
           
            axios.post('/undo-archive-events', {
                data: this.checkedRows
            }).then(res=>{
                if(res.data.status === 'undo'){
                    this.$buefy.toast.open({
                        message: 'Events archive successfully',
                        type: 'is-success'
                    })

                    this.loadAsyncData()
                }
            }).catch(err=>{
            
            })
        }

    },

    mounted() {
        this.loadAsyncData();
    },


}
</script>


<style scoped>
    .yes, .no, .pending {
        font-weight: bold;
        font-size: 12px;
        padding: 5px;
        color: white;
    }
    .yes{
        border: 1px solid green;
        background-color: green;
    }
    .no{
        border: 1px solid red;
        background-color: red;
    }

    .pending {
        border: 1px solid green;
        background-color: rgb(64, 97, 185);
    }

</style>
