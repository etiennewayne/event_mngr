<template>
    <div>


        <div class="container">
            <div class="columns is-centered">
                <div class="column is-6">
                    <div class="event box-shadow">
                        EVENT FEED
                    </div>
                    <div class="event-feed box-shadow" v-for="(event, index) in eventFeeds" :key="index">
                        <div class="event-title">
                            {{ event.event }}
                        </div>
                        <div class="event-date">
                            EVENT DATE: {{ new Date(event.event_date_from).toLocaleString('default', {month:'long', day:'numeric', year:'numeric'}) }}
                        </div>
                        <div class="event-date">
                            FROM: {{ new Date('2023-01-01 ' + event.event_time_from).toLocaleTimeString() }}
                            -
                            {{ new Date('2023-01-01 ' + event.event_time_to).toLocaleTimeString() }}
                        </div>
                        <div class="posted-date">
                            POSTED: {{ event.created_at | formatDateTime }}
                        </div>
                        <hr>
                        <div class="event-content ml-5">
                            <div v-html="event.event_content"></div>

                        </div>

                        <div class="img-container">
                            <img :src="`/storage/events/${event.img_path}`" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>

export default {

    data(){
        return {
            eventFeeds: [],
        }
    },

    methods: {
        loadEventFeeds(){
            axios.get('/load-event-feeds').then(res=>{
                this.eventFeeds = res.data.data
            })
        }
    },

    mounted() {
        this.loadEventFeeds()
    }
}
</script>


<style scoped>

.event{
   margin: 15px 0 15px 0;
   padding: 15px;
   font-weight: bold;
   font-size: 1.2em;
   border: 1px solid gray;
}
.event-feed{
    margin: 5px 0 15px 0;
    padding: 15px;
}

.event-title{
    font-weight: bold;
    margin-bottom: 5px;
}
.event-date{
    font-size: 12px;
    color: gray;
    font-weight: bold;
}
.posted-date{
    margin: 0 0 5px 0;
    font-size: 12px;
    color: gray;
    font-weight: bold;
}
.box-shadow{
    -webkit-box-shadow: 0 1px 6px -3px #777;
    -moz-box-shadow: 0 1px 6px -3px #777;
    box-shadow: 0 1px 6px -3px #777;
}
</style>
