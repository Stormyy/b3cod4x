export class B3ServerRepository {
	static servers = {};
	static async getServer(serverId){
		if (typeof this.servers[serverId] === 'undefined') {

			await axios.get('/b3/' + serverId + '').then((response) => {
				this.servers[serverId] = response.data;
			});
		}

		return this.servers[serverId];

	}
}