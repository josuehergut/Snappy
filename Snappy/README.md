<h1>Syncrhonize</h1>
Es un framework desarrollado para hacer consultas MySQL para PHP.

<h1>Instalación</h1>
<pre>
	include 'Async.class.php';
	use Async/query;
	$var = new query(-url_db, -user, -pass, -db_name);
</pre>
<h1>to()</h1>
selecciona una tabla.
<pre>
	$var->to(-table);
</pre>
<h1>add()</h1>
Añade un nuevo campo a la tabla seleccionada.
<pre>
	$var->to(-table)->add(['@param1' => 'val', '@param2' => 'val']);
</pre>
<h1>remove()</h1>
Elimina uno o mas campos de una tabla.
<p>Sin condición</p>
<pre>
	$var->to(-table)->remove();
</pre>
<p>Con condiciones</p>
<pre>
	//cuando Id sea igual a 1
	$var->to(-table)->when(['Id' => '1'])->remove();
	//cuando Id
	$var->to(-table)->when('Id')->remove();
</pre>
<h1>replace()</h1>
cambia los datos de un campo.
<pre>
	//cuando  Id sea igual  a 1
	$var->to(-table)->when(['Id' => '1'])->replace(['@param1' => 'val', '@param2' => 'val']);
	//cuando Id
	$var->to(-table)->when('Id')->replace(['@param1' => 'val', '@param2' => 'val']);
</pre>
<h1>get()</h1>
Retorna los datos buscados.
<pre>
	//retorna un campo que sea igual a val
	$sql = $var->to(-table)->when(['@param' => 'val'])->get();

	//retorna un campo que sea mayor a val
	$sql = $var->to(-table)->when(['@param' => 'val'], '>')->get();

	//retorna el campo Id
	$sql = $val->to(-table)->when('@param1')->get();
	
	//retorna un campo el cual este entre el 1 y el 3
	$sql = $val->to(-table)->when('@param1')->middle('1', '3')->get();
	
	//retorna el parametro con un limite
	$sql = $val->to(-table)->when('Id')->limit('0, 3');
</pre>