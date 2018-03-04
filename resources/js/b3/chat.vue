<template>
    <div>
        <div class="row">
            <form method="post" @submit.prevent="sendMessage" v-if="canchat">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Send message</label>
                        <input type="text" class="form-control" id="message" v-model="message" maxlength="100">
                    </div>
                </div>
                <div class="col-sm-6" style="margin-top:30px;">
                    <button type="submit" class="btn btn-primary">Send!</button>
                </div>
            </form>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Time</th>
                <th>Player</th>
                <th>Message</th>
                <th>Type</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="chatlog in chatlogs">
                <td>{{chatlog.msg_time}}</td>
                <td><a :href="'/b3/'+serverid+'/player/'+chatlog.player.guid">{{chatlog.client_name}}</a></td>
                <td>{{chatlog.msg}}</td>
                <td>{{chatlog.msg_type}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                query: "",
                chatlogs: [],
                message: "",
                interval: null,
                isActive: false,
            }
        },
        props: {
            serverid: {required: true},
            canchat: {required: true}
        },
        methods: {
            refresh(){
                if(this.isActive) {
                    axios.get('/b3/' + this.serverid + '/chat/').then((response) => {
                        this.chatlogs = response.data;
                    })
                }
            },
            sendMessage(){
                axios.post('/b3/' + this.serverid + '/chat', {'message': this.message}).then((response) => {
                    this.refresh();
                });
                this.message = "";
            }
        },
        created(){
            this.interval = setInterval(function () {
                this.refresh();
            }.bind(this), 1 * 1000);
        },

        watch: {
            '$parent.isActive': function(newValue, oldValue){
                this.isActive = newValue;
                if(newValue !== oldValue){
                    this.refresh();
                }
            }
        },

        beforeDestroy(){
            clearInterval(this.interval);
        }
    }
</script>