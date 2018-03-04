<template>
    <div>
        <button class="btn btn-danger" @click="show=true">Ban</button>
        <modal :title="'Ban '+player.name" v-model="show" @ok="banPlayer" @cancel="show=false" :large="true"
               ok-text="Ban!">
            <div class="form-group">
                <label for="reason">Reason</label>
                <input id="reason" class="form-control" v-model="reason" required>
            </div>
            <checkbox v-model="permanent" :true-value="true">Permanent</checkbox>
            <div class="form-group">
                <label style="display: block">Time</label>
                <div class="row">
                    <div class="col-sm-2">
                        <input type="text" class="form-control" v-model="duration">
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control" v-model="durationType">
                            <option value="minute">Minutes</option>
                            <option value="hour">Hours</option>
                            <option value="day">Days</option>
                            <option value="week">Weeks</option>
                            <option value="month">Months</option>
                            <option value="year">Years</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <h2>Proof</h2>
            <vue-select-image :dataImages="screenshotsPicker" @onselectimage="changedProof">
            </vue-select-image>

            <div class="form-group">
                <label for="noProof">
                    <input type="checkbox" id="noProof" v-model="noProof" :value="true">
                    I have no proof but i checked the player {{player.name}} throughly enough that im sure that he or she should not be on this server
                </label>
            </div>
        </modal>
    </div>
</template>

<script>
    import {modal, checkbox} from 'vue-strap';
    import VueSelectImage from 'vue-select-image';

    export default {
        components: {
            modal, checkbox, VueSelectImage
        },
        data(){
            return {
                show: false,
                permanent: false,
                reason: "",
                duration: 1,
                durationType: 'minute',
                proof: null,
                noProof: false,
                screenshotsPicker: []
            }
        },
        props: {
            player: {required: true},
            serverid: {required: true},
            screenshots: {required: false}
        },
        methods: {
            banPlayer(){
                if (this.reason === "") {
                    swal("Ban " + this.player.name, "Reason is required!", "error");
                    return;
                }

                if ((this.proof === null || this.proof === "" || typeof this.proof === 'undefined' )&& this.noProof === false) {
                    swal("Ban " + this.player.name, "Proof is required!", "error");
                    return;
                }

                let data = {reason: this.reason};
                data.permanent = this.permanent;
                data.duration = this.duration;
                data.durationType = this.durationType;
                data.proof = this.proof;

                axios.post('/b3/' + this.serverid + '/ban/' + this.player.guid, data).then((response) =>{
                    swal("Ban " + this.player.name, "Player banned", "success");
                });

                this.show = false;
            },

            changedProof(selectedScreenshot){
                this.proof = selectedScreenshot.id;
            }
        },
        mounted(){
            this.screenshots.forEach(screenshot => {
                this.screenshotsPicker.push({
                    id: screenshot.id,
                    src: screenshot.url,
                    alt: screenshot.created_at
                })
            });
        }
    }
</script>

<style>
    .vue-select-image__item {
        width:50%;
        margin: 0 !important;
    }

    .vue-select-image__thumbnail {
        margin-left:10px;
        margin-right:10px;
    }
</style>