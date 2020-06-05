    var app = new Vue({
        el: '#vueapp',
        data: {
            id: '',
            first_name: '',
            last_name: '',
            username: '',
            email: '',
            users: [],
            action: null,
            search_text: null,
            modalNo: null,
        },
        mounted: function() {
            this.getUsers()
        },
        computed: {
            sortedUser: function() {
                return this.users.slice().sort(function(a, b) {
                    return a.first_name > b.first_name ? 1 : -1;
                });
            }
        },
        methods: {
            toastMessage: function(msj) {
                var toastHTML = '<span>' + msj + '</span>';
                M.toast({
                    html: toastHTML,
                    classes: 'rounded'
                });
            },
            getUsers: function() {
                var that = this;
                axios.get('api/users.php?action=read')
                    .then(function(response) {
                        that.users = response.data;
                    })
                    .catch(function(error) {
                        that.toastMessage("Kullanıcı listeleme işleme esnasında hata:" + error);
                    });
            },
            addUser: function() {
                var formData = this.createFormData();
                var that = this;
                axios({
                        method: 'POST',
                        url: 'api/users.php?action=create',
                        data: formData,
                    })
                    .then(function(response) {
                        that.users.push(formData)
                        that.resetForm();
                        that.modalClose();
                        that.toastMessage(response.data)
                    })
                    .catch(function(response) {
                        that.toastMessage(response.data)
                    });
            },
            updateUser() {
                var formData = this.createFormData();
                var that = this;
                axios({
                        method: 'POST',
                        url: 'api/users.php?action=update',
                        data: formData,
                    })
                    .then(function(response) {
                        let userIndex = that.users.findIndex(user => user.email === that.email);
                        user = {
                            'first_name': that.first_name,
                            'last_name': that.last_name,
                            'username': that.username,
                            'email': that.email,
                        }
                        that.users.splice(userIndex, 1, user);
                        that.resetForm();
                        that.modalClose();
                        that.toastMessage(response.data)
                    })
                    .catch(function(response) {
                        that.toastMessage(response)
                    });
            },
            userDelete() {
                var user = {};
                user['email'] = this.email;
                var that = this;
                axios({
                        method: 'POST',
                        url: 'api/users.php?action=delete',
                        data: user,

                    })
                    .then(function(response) {
                        that.users = app.users.filter(user => user.email !== that.email);
                        that.modalClose();
                        that.toastMessage(response.data)
                    })
                    .catch(function(error) {
                        that.toastMessage(error)
                    });
            },
            searchUser() {
                var formData = {};
                var that = this;
                formData['search_text'] = this.search_text;
                axios({
                        method: 'POST',
                        url: 'api/users.php?action=search',
                        data: formData,

                    })
                    .then(function(response) {
                        that.users = response.data;
                    })
                    .catch(function(error) {
                        that.toastMessage("Kullanıcı arama işleme esnasında hata:" + error);
                    });
            },
            resetForm: function() {
                this.first_name = "";
                this.last_name = "";
                this.username = "";
                this.email = "";
            },
            createFormData() {
                let formData = new FormData();
                formData.append('first_name', this.first_name)
                formData.append('last_name', this.last_name)
                formData.append('username', this.username)
                formData.append('email', this.email)

                var user = {};
                formData.forEach((value, key) => {
                    user[key] = value;
                });
                return user;
            },
            modalOpen(no, email) {
                this.modalNo = no;
                if (no === 2) {
                    let user = this.getUserIndex(email)
                    this.first_name = user.first_name;
                    this.last_name = user.last_name;
                    this.username = user.username;
                    this.email = email;
                } else if (no === 3) {
                    let user = this.getUserIndex(email);
                    this.email = email;
                    this.first_name = user.first_name;
                    this.last_name = user.last_name;
                }
            },
            getUserIndex(email) {
                return this.users.find(user => user.email === email);
            },

            modalClose() {
                this.modalNo = null;
                this.resetForm();
            }
        }
    })