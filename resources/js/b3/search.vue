<template>
    <div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" class="form-control" v-model="query" @change="search">
                </div>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Playername</th>
                <th>Guid</th>
                <th>Ip</th>
                <th>Connections</th>
                <th>Last seen</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="player in players">
                <td>{{player.id}}</td>
                <td v-text="player.name"></td>
                <td><a :href="'/b3/'+serverid+'/player/'+player.guid">{{player.guid}}</a></td>
                <td v-html="player.ip"></td>
                <td>{{player.connections}}</td>
                <td>{{player.lastseen}}</td>
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
                players: []
            }
        },
        props: {
            serverid: {required: true}
        },
        watch: {
            query: function (value) {
                axios.get('/b3/' + this.serverid + '/search/' + this.query).then((response) => {
                    this.players = response.data;
                })
            }
        },
        methods: {
            search(){
                axios.get('/b3/' + this.serverid + '/search/' + this.query).then((response) => {
                    this.players = response.data;
                })
            }
        }
    }
</script>