export class B3ServerPermissionRepository {

	static permissions = {};

	static async getPermissions(serverId) {

		if (typeof this.permissions[serverId] === 'undefined') {
			await axios.get('/b3/' + serverId + '/permissions').then((response) => {
				this.permissions[serverId] = response.data;
			});
		}

		return this.permissions[serverId];

	}
}