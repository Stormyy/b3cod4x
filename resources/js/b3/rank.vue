<template>
    <div>
        <form method="post" @submit.prevent="setRank">
            <div class="row">
                <div class="col-sm-11">
                    <select class="form-control" v-model="rank">
                        <option value="128">Super Admin</option>
                        <option value="64">Senior Admin</option>
                        <option value="32">Full admin</option>
                        <option value="16">Admin</option>
                        <option value="8">Moderator</option>
                        <option value="2">Regular</option>
                        <option value="1">User</option>
                        <option value="0">Guest</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-danger" @click="show=true">Set rank!</button>
                </div>

            </div>
        </form>

    </div>
</template>

<script>
    import {modal, checkbox} from 'vue-strap';

    export default {
        components: {
            modal, checkbox
        },
        data(){
            return {
                rank: null
            }
        },
        props: {
            player: {required: true},
            serverid: {required: true}
        },
        methods: {
            setRank(){
                axios.post('/b3/'+this.serverid+'/'+this.player.guid+'/rank', {rank: this.rank}).then((response) => {
                    swal("Set rank", "Rank has been set of the player  " + this.player.name + "!\n", "success");
                }).catch((error) => {
                    swal("Set rank", "You have not enough rights to set the rank of " + this.player.name+ "\n", "error");
                })
            }
        },
        created(){
            this.rank = this.player.group_bits;
        }
    }
</script>