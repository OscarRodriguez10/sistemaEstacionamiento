<template>
	<div>
		<h1 class="text-center"> Registro de Vehiculos</h1>
		<hr>
		<!-- Button trigger modal -->
			<button @click="update=false; openModal();" type="button" class="btn btn-primary">
			  Nuevo Vehiculo
			</button>
			<a target="_blank" v-bind:href="'/reporteVehiculos'"><button class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i>Reporte</button></a>
			<!-- Modal -->
			<div class="modal" :class="{show:modal}">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{titleModal}}</h5>
                <button @click="closeModal();" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
			      <div class="modal-body">
			        <div>
			        	<label for="descripcion">Descripcion</label>
			        	<input v-model="vehiculo.descripcion" type="text" class="form-control" id="descripcion" placeholder="Descripcion" name="descripcion">
			        </div>
			        <div>
			        	<label for="numeroPlaca">Numero Placa</label>
			        	<input v-model="vehiculo.numeroPlaca" type="text" class="form-control" id="numeroPlaca" placeholder="Numero Placa" name="numeroPlaca">
			        </div>
			        <!-- PROBAR BOTON-->
			        <div>
			        	<label for="idTipoVehiculo">Tipo Vehiculo</label>
                		<select class="form-control" v-model="vehiculo.idTipoVehiculo">
                        	<option>Seleccionar Tipo Vehiculo</option>
    						<option v-for="tipoVehiculo in tipoVehiculos" v-bind:value="tipoVehiculo.idTipoVehiculo" >{{ tipoVehiculo.descripcion }}</option>
                    	</select>
                 	</div>
			        <div>
			        	<label for="estado">Estado</label>
                		<select v-model="vehiculo.estado" class="form-control">
                        	<option value="Activo">Activo</option>
                        	<option value="Inactivo">Inactivo</option>
                    	</select>
                  	</div>
			      </div>
			      <div class="modal-footer">
			          <button @click="closeModal();" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button @click="save();" type="button" class="btn btn-success">Guardar Cambios</button>
			      </div>
			    </div>
			  </div>
			</div>
			<table class="table table-striped table-hover">
  				<thead class="table-dark">
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Descripcion</th>
			      <th scope="col">Numero Placa</th>
			      <th scope="col">Tipo Vehiculo</th>
			      <th scope="col">Estado</th>
			      <th scope="col" colspan="2" class="text-center">Accion</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr v-for="vehiculo in vehiculos" :key="vehiculo.idvehiculo">
			      <th scope="row">{{ vehiculo.idvehiculo}}</th>
			      <td>{{ vehiculo.descripcion}}</td>
			      <td>{{ vehiculo.numeroPlaca}}</td>
			      <td>{{ vehiculo.tipo.descripcion}}</td>
			      <td>{{ vehiculo.estado}}</td>
			      <td>
			      	<button @click="update=true; openModal(vehiculo);" class="btn btn-warning">Editar</button>
			      </td>
			      <td>
			      	<button @click="eliminar(vehiculo.idvehiculo)" class="btn btn-danger">Eliminar</button>
			      </td>
			    </tr>
			  </tbody>
			</table>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				vehiculo: {
					descripcion:'',
					numeroPlaca:'',
					estado:''
				},

				id:0,
				update:true,
				modal:0,
				titleModal:'',
				vehiculos:[],

				tipoVehiculo: {
					descripcion:'',
					costo:'',
					estado:''
				},

				tipoVehiculos:[],
			}
		},	
		mounted: function () {
        	const {data} = this;
        	console.log("test");
        	this.listaTipoVehiculo();
       	},
		methods: {
			async list(){
				const res = await axios.get('vehiculos');
				this.vehiculos = res.data;
			},
			
			async listaTipoVehiculo(){
				console.log(this.tipoVehiculos);
				const res = await axios.get('tipoVehiculos');
				this.tipoVehiculos = res.data;
				console.log(this.tipoVehiculos);
			},

			async eliminar(idvehiculo){
				const res = await axios.delete('/vehiculos/' + idvehiculo);
				this.list();
			},
			async save() {

                if(this.update)
                {
                    const res = await axios.put('/vehiculos/' + this.id, this.vehiculo);
                }else{
                	//console.log(this.vehiculo);
                    const res = await axios.post('/vehiculos', this.vehiculo);
                }
                this.closeModal();
                this.list();
            },
			openModal(data={}){
				this.modal=1;
				if(this.update){
					this.id = data.idvehiculo;
					this.titleModal="Modificar Vehiculo";
					this.vehiculo.descripcion = data.descripcion;
					this.vehiculo.numeroPlaca = data.numeroPlaca;
					this.vehiculo.estado = data.estado;
				}else{
					this.id=0;
					this.titleModal ="Crear Vehiculo";
					this.vehiculo.descripcion ='';
					this.vehiculo.numeroPlaca = '';
					this.vehiculo.estado = '';
				} 

			},
			closeModal(){
				this.modal=0;
			},
		},

		created(){
			this.list();
		},
	}
</script>
<style>
	.show {
	display: list-item;
	opacity: 1;
	background: rgba(44, 38, 75, 0.849);
	}
</style>