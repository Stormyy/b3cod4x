<template>
    <tbody>
    <tr v-for="player in players">
        <td>{{player.DBID}}</td>
        <td>{{player.Name}}</td>
        <td><a :href="'/b3/'+serverid+'/player/'+player.GUID">{{player.GUID}}</a></td>
        <td>{{player.IP}}</td>
        <td>{{player.screenshots.length}}</td>
        <td v-html="latestScreenshot(player)"></td>
        <td v-if="player.AllowScreenshot"><button @click="postScreenshot(player)" class="btn btn-primary">Screenshot</button></td>
    </tr>
    </tbody>
</template>

<script>
    export default {
        data(){
            return {
                players: []
            }
        },
        props: {
            serverid: {required: true}
        },
        watch: {},
        methods: {
            latestScreenshot(player){
                let latestScreenshot = player.screenshots[0];
                if(latestScreenshot !== undefined){
                    return '<a href="'+latestScreenshot.url+'" data-fancybox="gallery" data-caption="'+player.Name+'">'+latestScreenshot.created_at+'</a>';
                }
            },
            reloadPlayers(callback=null){
                axios.get('/b3/'+this.serverid+'/player').then(response => {
                    this.players = response.data;
                    if(callback !== null) {
                        callback(this);
                    }
                })
            },
            postScreenshot(player){
                axios.post('/b3/'+this.serverid+'/screenshot/api', {"guid": player.GUID}).then(response => {
                    swal("Screenshot", "Screenshot request send for "+player.Name+"\n"+response.data.split('print')[1], "success");
                });
            }
        },
        created(){
            this.reloadPlayers();
            setInterval(function () {
                this.reloadPlayers();
            }.bind(this), 10 * 1000);

            if(this.$echo !== undefined) {
                this.$echo.channel('screenshots.' + this.serverid).listen('ScreenshotTaken', (data) => {
                    this.reloadPlayers(function (self) {
                        self.$root.$refs.toastr.Add({
                            title: "Screenshot has been taken", // Toast Title
                            msg: "Screenshot of player " + data.screenshot.name + " has been uploaded", // Message
                            clickClose: true, // Click Close Disable
                            closeOnHover: true,
                            timeout: 5 * 60 * 1000, // Remember defaultTimeout is 5 sec..
                            position: "toast-top-full-width", // Toast Position.
                            type: "success" // Toast type
                        });
                    })
                });
            }
        },
    }
</script>