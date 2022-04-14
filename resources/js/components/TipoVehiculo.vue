<template>
	<div>
		<h1 class="text-center"> Registro de Tipo Vehiculos</h1>
		<hr>
		<!-- Button trigger modal -->
			<button @click="update=false; openModal();" type="button" class="btn btn-primary">
			  Nuevo Tipo
			</button>
			<a target="_blank" v-bind:href="'/reporteTipoVehiculos'"><button class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i>Reporte</button></a>
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
			        	<input v-model="tipoVehiculo.descripcion" type="text" class="form-control" id="descripcion" placeholder="Descripcion" name="descripcion">
			        </div>
			        <div>
			        	<label for="costo">Costo</label>
			        	<input v-model="tipoVehiculo.costo" type="decimal" class="form-control" id="costo" placeholder="Costo" name="costo">
			        </div>
			        <div>
			        	<label for="estado">Estado</label>
                		<select v-model="tipoVehiculo.estado" class="form-control" id="estado" name="estado" >
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
			      <th scope="col">Costo</th>
			      <th scope="col">Estado</th>
			      <th scope="col" colspan="2" class="text-center">Accion</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr v-for="tipoVehiculo in tipoVehiculos" :key="tipoVehiculo.idTipoVehiculo">
			      <th scope="row">{{ tipoVehiculo.idTipoVehiculo}}</th>
			      <td>{{ tipoVehiculo.descripcion}}</td>
			      <td> $ {{ tipoVehiculo.costo}}</td>
			      <td>{{ tipoVehiculo.estado}}</td>
			      <td>
			      	<button @click="update=true; openModal(tipoVehiculo);" class="btn btn-warning">Editar</button>
			      </td>
			      <td>
			      	<button @click="eliminar(tipoVehiculo.idTipoVehiculo)" class="btn btn-danger">Eliminar</button>
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
				tipoVehiculo: {
					descripcion:'',
					costo: '',
					estado:''
				},
				id:0,
				update:true,
				modal:0,
				titleModal:'',
				tipoVehiculos:[],
			}
		},
		methods: {
			async list(){
				const res = await axios.get('tipoVehiculos');
				this.tipoVehiculos = res.data;
			},
			async eliminar(idTipoVehiculo){
				const res = await axios.delete('/tipoVehiculos/' + idTipoVehiculo);
				this.list();
			},
			async save() {

                if(this.update)
                {
                    const res = await axios.put('/tipoVehiculos/' + this.id, this.tipoVehiculo);
                }else{
                	//console.log(this.tipoVehiculo);
                    const res = await axios.post('/tipoVehiculos', this.tipoVehiculo);
                }
                this.closeModal();
                this.list();
            },
			openModal(data={}){
				this.modal=1;
				if(this.update){
					this.id = data.idTipoVehiculo;
					this.titleModal="Modificar Tipo Vehiculo";
					this.tipoVehiculo.descripcion = data.descripcion;
					this.tipoVehiculo.costo = data.costo;
					this.tipoVehiculo.estado = data.estado;
				}else{
					this.id=0;
					this.titleModal ="Crear Tipo Vehiculo";
					this.tipoVehiculo.descripcion ='';
					this.tipoVehiculo.costo = '';
					this.tipoVehiculo.estado = '';
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