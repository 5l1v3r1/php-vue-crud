<!DOCTYPE html>
<html>

<head>
    <title>CRUD APP</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="assets/css/app.css">


    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <div id="vueapp">
        <nav class="blue lighten-1">
            <div class="nav-wrapper container">
                
                <a href="#" class="brand-logo">
                    <img src="assets/img/logo.png" alt="Logo" width="32">
                    CRUD Application
                </a>
            </div>
        </nav>

        <div class="container">
            <div>
                <h3 class="center">Kullanıcılar<a class="btn-floating btn-large waves-effect waves-light green right" @click="modalOpen(1)"><i class="material-icons">add</i></a></h3>
            </div>
            <div class="row">
                <form class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">search</i>
                            <input id="icon_prefix" type="text" class="validate" v-model="search_text" @change="searchUser()" @keyup="searchUser()">
                            <label for="icon_prefix">Kullanıcı Ara</label>
                        </div>

                    </div>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Adı</th>
                        <th>Soyadı</th>
                        <th>Kullanıcı Adı</th>
                        <th>Email</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody v-if="users.length===0">
                    <tr>
                        <td colspan="5">
                            <h4 class="center">Kullanıcı Bulunamadı!</h4>
                        </td>
                    </tr>
                </tbody>
                <tbody v-for="user in sortedUser">
                    <tr>
                        <td>{{user.first_name}}</td>
                        <td>{{user.last_name}}</td>
                        <td>{{user.username}}</td>
                        <td>{{user.email}}</td>
                        <td>
                            <div class="left">
                                <a @click="modalOpen(2,user.email)">
                                    <i class="material-icons">edit</input>
                                </a>
                                <a @click="modalOpen(3,user.email)"><i class="material-icons red-text">delete</i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="modal-overlay" :class="modalNo ? 'd-block': ''">

        </div>

        <!--MODAL INSERT AND UPDATE -->
        <div id="modal1" class="modal modal-fixed-footer" tabindex="0" :class="modalNo===1 || modalNo===2 ? 'd-block': ''">
            <div class="modal-content">
                <h4>{{modalNo===1 ? 'Kullanıcı Ekle':'Kullanıcı Bilgileri Güncelle'}}</h4>
                <div class="row">
                    <form class="col s12">
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="first_name" type="text" class="validate" name="first_name" v-model="first_name">
                                <label for="first_name" :class="modalNo===2 ? 'active':''">Adı</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="last_name" type="text" class="validate" name="last_name" v-model="last_name">
                                <label for="last_name" :class="modalNo===2 ? 'active':''">Soyadı</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="username" type="text" class="validate" name="username" v-model="username">
                                <label for="username" :class="modalNo===2 ? 'active':''">Kullanıcı Adı</label>
                            </div>
                            <div class="input-field col s6" v-if="modalNo===1">
                                <input id="email" type="email" class="validate" name="email" v-model="email">
                                <label for="email" >Email</label>
                            </div>
                            <div class="input-field col s6" v-else-if="modalNo===2">
                                <input id="email" type="email" class="validate" name="email" v-model="email" disabled>
                                <label for="email" :class="modalNo===2 ? 'active':''">Email</label>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat red white-text" @click="modalClose()">İptal Et</a>
                <a href="#!" class="modal-close waves-effect waves-green btn-flat green white-text" @click="addUser()" v-if="modalNo===1">Ekle</a>
                <a href="#!" class="modal-close waves-effect waves-green btn-flat green white-text" @click="updateUser()" v-else-if="modalNo===2">Güncelle</a>
                <div>
                </div>
            </div>
        </div>

        <!-- Modal Delete -->
        <div id="modal-delete" class="modal" :class="modalNo===3 ? 'd-block': ''">
            <div class="modal-content">
                <h4>Kullanıcı Silme</h4>
                <p>{{first_name }} {{last_name}} kullanıcısını silmek istediğinizden emin misiniz ?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-green btn-flat red white-text" @click="modalClose()">Hayır</a>
                <a href="#!" class="modal-close waves-effect waves-green btn-flat green white-text" @click="userDelete()">Evet</a>
            </div>
        </div>
    </div>


</body>

</html>

<script  src="https://cdn.jsdelivr.net/npm/vue" ></script>
<script  src="https://unpkg.com/axios/dist/axios.min.js" ></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js" ></script>
<script  src="assets/js/app.js" ></script>