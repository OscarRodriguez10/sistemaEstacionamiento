<template>
	<div>
		<h1 class="text-center"> Registro de Salida</h1>
		<hr>
		<!-- Button trigger modal -->
			<button @click="update=false; openModal();" type="button" class="btn btn-primary">
			  Nueva Salida
			</button>
			<a target="_blank" v-bind:href="'/reporteSalida'"><button class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i>Reporte</button></a>
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
			        	<label for="idvehiculo">Vehiculo</label>
                		<select class="form-control" v-model="salida.idvehiculo">
                        	<option>Seleccionar Vehiculo</option>
    						<option v-for="vehiculo in vehiculos" v-bind:value="vehiculo.idvehiculo" >{{ vehiculo.descripcion }} {{ vehiculo.numeroPlaca }}</option>
                    	</select>
                 	</div>
     				<div>
     					<label><b>Encargado del Vehiculo:</b> {{encargado_vehiculo}}</label><br>
     					<label><b>Tipo Vehiculo:</b> {{nombre_tipoVehiculo}}</label><br>
                 		<label><b>Minutos:</b> {{minutos}}</label><br>
                 		<label><b>Total:</b> $ {{total}}</label>

                 	</div>
			      </div>
			      <div class="modal-footer">
			    <button @click="closeModal();" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button @click="listaEntrada();" type="button" class="btn btn-primary" data-dismiss="modal">Calcular</button>
                <button @click="save();" type="button" class="btn btn-success">Pagar</button>
			      </div>
			    </div>
			  </div>
			</div>
			<table class="table table-striped table-hover">
  				<thead class="table-dark">
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Vehiculo</th>
			      <th scope="col">Fecha_Entrada</th>
			      <th scope="col">Fecha_Salida</th>
			      <th scope="col">Duracion</th>
			      <th scope="col">Total Pagado </th>
			      <th scope="col">Estado</th>
			      <th scope="col" colspan="2" class="text-center">Accion</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr v-for="salida in salidas" :key="salida.idsalida">
			      <th scope="row">{{ salida.idSalida}}</th>
			      <td>{{ salida.vehiculo.descripcion}}</td>
			      <td>{{ salida.fecha_entrada}}</td>
			      <td>{{ salida.fecha_salida}}</td>
			      <td>{{ salida.duracion}}</td>
			      <td>$ {{ salida.cobro }}</td>
			      <td>{{ salida.estado}}</td>

			      <td>
			      	<button @click="eliminar(salida.idSalida)" class="btn btn-danger">Eliminar</button>
			      </td>
			      
			    </tr>
			  </tbody>
			</table>
	</div>
</template>

<script>
	import moment from 'moment';
	export default{
		data () {
			return {
				salida: {
					idSalida: '',
					idvehiculo:'',
					fecha_entrada: '',
					fecha_salida:'',
					duracion:'',
					cobro:'',
					idusuario:'',
					idEntrada:'',
					estado:''
				},
				id:0,
				update:true,
				modal:0,
				titleModal:'',
				salidas:[],

				vehiculo: {
					idvehiculo:'',
					descripcion:'',
					numeroPlaca:'',
					estado:''
				},

				vehiculos:[],

				entrada: {
					identrada:'',
					idvehiculo:'',
					idusuario:'',
					fecha_hora: '',
					estado:''
				},
				entradas:[],		
				minutos:0,
				total:0,	
				nombre_tipoVehiculo:'',	
				encargado_vehiculo:'',
			}
		},
		mounted: function () {
        	const {data} = this;
         	this.listaVehiculo();
       	},
		methods: {
			async list(){
				const res = await axios.get('salidas');
				this.salidas = res.data;
			},
			async listaVehiculo(){
				const res = await axios.get('vehiculos');
				this.vehiculos = res.data;
			},

			async listaEntrada(idvehiculo){

				const res = await axios.get('/entradas/' + this.salida.idvehiculo);
				this.entradas = res.data;
				console.log(this.entradas);


				var fecha1 = moment(this.entradas.fecha_hora, "YYYY-MM-DD HH:mm:ss");
				var fecha2 = moment().format('YYYY-MM-DD HH:mm:ss');
				var fecha3 = moment(fecha2, "YYYY-MM-DD HH:mm:ss");
				var minutos = fecha3.diff(fecha1, 'm'); // Diff in days
				console.log(minutos);
				this.minutos = minutos;
				this.total = this.entradas.vehiculo.tipo.costo * minutos;
				this.nombre_tipoVehiculo = this.entradas.vehiculo.tipo.descripcion;
				this.encargado_vehiculo = this.entradas.nombre;

			},

			async eliminar(idSalida){
				console.log(idSalida);
				const res = await axios.delete('/salidas/' + idSalida);
				this.list();
			},
			async save() {

                if(this.update)
                {

                    const res = await axios.put('/salidas/' + this.id, this.salida);
                }else{
                	console.log(this.entradas.idEntrada);

                	const salida2 = {
    					idsalida: '',
						idvehiculo: this.salida.idvehiculo,
						fecha_entrada: this.entradas.fecha_hora,
						fecha_salida:'',
						duracion: this.minutos,
						cobro: this.total,
						idusuario:'',
						idEntrada: this.entradas.idEntrada,
						estado:''
					};
                    const res = await axios.post('/salidas', salida2);
                }
                this.closeModal();
                this.list();
            },
			openModal(data={}){
				this.modal=1;
				if(this.update){
					this.id = data.idsalida;
					this.titleModal="Modificar Salida";
				}else{
					this.id=0;
					this.titleModal ="Registrar el pago del Estacionamiento";
					this.salida.estado = '';
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