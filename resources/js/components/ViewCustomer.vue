<template>
    <div>
        <div class="row mt-3">
            <div class="col-md-4">
                <h2><span><i class="fa fa-users"></i></span> Clientes</h2>
            </div>
            <div class="col-md-4 offset-md-4 shadow card-body">
                  <b-input-group class="">
                    <b-form-input v-model="search" placeholder="Buscar..."></b-form-input>
                    <b-input-group-append>
                    <b-button @click="searchCustomer" variant="outline-primary"><i class="fa fa-search"></i></b-button>
                    </b-input-group-append>
                </b-input-group>
            </div>
        </div><br><br>
        <div class="row">
            <div class="col-md-12 shadow card-body">
                    <b-button class="shadow-lg" pill v-b-modal.modal-customer  variant="primary">
                        <i class="fa fa-user-plus"></i> Nuevo Cliente
                    </b-button>
                    <p class="float-right text-muted mt-5">{{ this.table.customers.length }} registros</p>
                    <b-table id="tableCustomer" :per-page="perPage" :current-page="currentPage" responsive striped hover :items="table.customers" sort-icon-left :busy="isBusy" :fields="table.fields">
                        <template v-slot:table-busy>
                            <div class="text-center text-success my-5">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Cargando...</strong>
                            </div>
                        </template>
                        <template v-slot:cell(#)="data">
                            {{ data.index + 1 }}
                        </template>
                        <template v-slot:cell(acciones)="customers">
                            <b-button squared @click="showCustomer(customers.item.id)" v-b-tooltip.hover.topleft title="Ver mas" class="btn btn-info">
                                <i class="fa fa-info"></i>
                            </b-button>
                            <b-button squared href="/customers/"  v-b-tooltip.hover.topleft.v-success title="Abono" class="btn btn-success">
                                <i class="fa fa-hand-holding-usd"></i>
                            </b-button>
                        </template>
                    </b-table>
                    <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="tableCustomer"
                    align="center"
                    first-number
                    last-number
                    ></b-pagination>
            </div>
        </div>
        <b-modal id="modal-customer" ref="modalCustomer" title="Nuevo Cliente" size="lg">
            <b-form @submit.stop.prevent>
                <b-input-group class="form-group">
                    <b-form-input
                    id="input-1"
                    v-model="form.document"
                    type="text"
                    required
                    placeholder="Numero de documento"
                    ></b-form-input>
                </b-input-group>
                <b-input-group class="form-group">
                    <b-form-input
                    id="input-2"
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Nombres"
                    ></b-form-input>
                </b-input-group>
                <b-input-group class="form-group">
                    <b-form-input
                    id="input-3"
                    v-model="form.last_name"
                    type="text"
                    required
                    placeholder="Apellidos"
                    ></b-form-input>
                </b-input-group>
                <!-- <b-button type="submit" variant="primary">Submit</b-button>
                <b-button type="subit" variant="danger">Reset</b-button> -->
            </b-form>
                <template v-slot:modal-footer="{ cancel, newCustomer }">
                    <b-button size="sm" variant="danger" @click="cancel()">
                        Cancelar
                    </b-button>
                    <b-button @click="newCustomer()" size="sm" variant="primary">
                        Crear
                    </b-button>
                    
                </template>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                form:{
                    document:'',
                    name:'',
                    last_name:''
                },
                table:{
                    customers:[],
                    fields:[
                        '#',
                        {
                            key:'document',
                            label: 'Documento'
                        },
                        {
                            key:'name',
                            label: 'Nombres',
                            sortable: true
                        },
                        {
                            key:'last_name',
                            label: 'Apellidos'
                        },
                        'Acciones'
                    ]
                },
                isBusy: false,
                perPage: 3,
                currentPage: 1,
                search:''
            }

        },
       components:{
       },
        computed: {
      rows() {
        return this.table.customers.length
      }
    },
       mounted(){
           this.getCustomers()
              
           
       },
       methods:{
           getCustomers(){
               this.isBusy = true
                axios.get('/getCustomers')
               .then( res => {
                //    console.log(res);
                   this.table.customers = res.data
                   this.isBusy = false
               })
               .catch( err => {
                   console.error(err);
               });
           },
           newCustomer(){
            //    alert('cus')
               axios.post('/customers', {
                    document: this.form.document,
                    name:  this.form.name,
                    last_name:  this.form.last_name
                })
                .then(function (res) {
                    // console.log(res.data);
                    // this.alert = res.data
                })
                .catch(function (error) {
                    console.log(error);
                });
                var msg = 'Cliente guardado'
                var color = 'success'
                var title = '¡Bien Hecho!'
                this.alertOk(msg, color, title)
                this.hideModal()
                this.form = {}
                    this.getCustomers()
           },
            hideModal() {
                this.$refs['modalCustomer'].hide()
            },
            alertOk(msg,color,title) {
                // this.boxTwo = ''
                this.$bvModal.msgBoxOk(msg, {
                title: title,
                size: 'sm',
                buttonSize: 'sm',
                okVariant: color,
                headerClass: 'p-2 border-bottom-0',
                footerClass: 'p-2 border-top-0',
                centered: true
                })
                .then(value => {
                    // this.boxTwo = value
                })
                .catch(err => {
                    // An error occurred
                })
            },
            searchCustomer(id){
               this.isBusy = true
                axios.get('/searchCustomers?word='+this.search)
               .then( res => {
                //    console.log(res);
                   this.table.customers = res.data
                   this.isBusy = false
                   this.search = ''
               })
               .catch( err => {
                   console.error(err);
               });
            },
            showCustomer(id){
                
                window.location = "/customers/"+id;
                    
            }
       }
    }
</script>

<style lang="css" scoped>

</style>