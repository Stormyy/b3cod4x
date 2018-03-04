<template>
    <div>
        <h3 class="pull-right" style="margin:0" v-text="refreshTimerText"></h3>
        <table class="table table-striped">
            <thead>
            <slot></slot>
            </thead>
            <tbody>
            <tr v-for="player in players" :style="cssClass(player)">
                <td>{{player.DBID}}</td>
                <td v-text="player.Name"></td>
                <td><a :href="'/b3/'+serverid+'/player/'+player.GUID">{{player.GUID}}</a></td>
                <td v-html="player.IP"></td>
                <td>{{player.screenshots.length}}</td>
                <td v-html="latestScreenshot(player)"></td>
                <td v-if="isAllowedToScreenshot == true">
                    <button @click="postScreenshot(player)" class="btn btn-primary">Screenshot</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    const moment = require('moment-timezone');
    window['moment'] = moment;
    export default {
        data(){
            return {
                players: [],
                isAllowedToScreenshot: false,
                interval: null,
                isActive: false,
                timer: 0,
                refreshTime: 10
            }
        },
        props: {
            serverid: {required: true}
        },
        watch: {
            '$parent.isActive': function (newValue, oldValue) {
                this.isActive = newValue;
                if (newValue !== oldValue) {
                    this.reloadPlayers();
                }
            }
        },

        computed: {
            refreshTimerText(){
                if(this.timer > 0){
                    return `Refreshing in ${this.timer}..`;
                }

                return 'Refreshing...';
            }
        },

        methods: {
            latestScreenshot(player){
                let latestScreenshot = player.screenshots[0];
                if (latestScreenshot !== undefined) {
                    let currentDate = moment(moment().tz('Europe/Amsterdam').subtract(5, 'seconds').format('YYYY-MM-DD HH:mm'));
                    return '<a href="' + latestScreenshot.url + '" data-fancybox="gallery" data-caption="' + player.Name + '">' + moment(latestScreenshot.created_at).from(currentDate) + '</a>';
                }
            },
            reloadPlayers(callback = null){
                if (this.isActive) {
                    axios.get('/b3/' + this.serverid + '/player').then(response => {
                        this.players = response.data.players;
                        this.isAllowedToScreenshot = response.data.isAllowedToScreenshot;
                        if (callback !== null) {
                            callback(this);
                        }
                    })

                    this.timer = this.refreshTime;
                }
            },
            postScreenshot(player){
                axios.post('/b3/' + this.serverid + '/screenshot/api', {"guid": player.GUID}).then(response => {
                    swal("Screenshot", "Screenshot request send for " + player.Name + "\n" + response.data.split('print')[1], "success");
                });
            },
            cssClass(player){
                if (player.bannedOnOtherServers === true) {
                    return 'background-color:#ff6d6b; color:white;'
                }
                return '';
            }
        },
        created(){
            this.reloadPlayers();
            this.interval = setInterval(function () {

                if (this.timer < 0) {
                    this.reloadPlayers();
                } else {
                    this.timer -= 1;
                }

            }.bind(this), 1 * 1000);

            if (this.$echo.options.key !== "") {
                this.$echo.channel('screenshots.' + this.serverid).listen('ScreenshotTaken', (data) => {
                    this.reloadPlayers(function (self) {
                        let msg = "Screenshot of player " + data.screenshot.name + " has been uploaded";
                        if(data.takenBy !== null){
                            msg = `Screenshot of player ${data.screenshot.name} taken by ${data.takenBy.name} has been uploaded`
                        }

                        self.$root.$refs.toastr.Add({
                            title: "Screenshot has been taken", // Toast Title
                            msg: msg, // Message
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

        beforeDestroy() {
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
        },
    }
</script>