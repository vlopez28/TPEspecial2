# TP Especial Parte II

## API Doc

#### **BASE URL:** http://{host_name}/api
<br>

## GET **/properties** Obtiene todas las propiedades  

### Params  

> #### Query
>> #### **sort:** Columna por la que se desea ordenar. Case insensitive. Posibles valores ***[id, direccion, habitaciones, patio, banios, tipo_contrato, moneda, precio]***. Valor por defecto ***id***

>> #### **order:** Tipo de ordenamiento. Case insensitive. Posibles valores ***[asc, desc]***. Valor por defecto ***asc***

>> #### **search:** Filtro de búsqueda por tipo de propiedad.

>> #### **limit:** Número entero que especifica la cantidad de registros máximos a devolver. Valor por defecto ***100***

>> #### **offset:** Número entero que especifica la cantidad de registros que no seran tenidos en cuenta. Valor por defecto ***0***
<br>

## GET /properties/{id} Obtiene una propiedad por ID

### Params

> #### {Path}
>> #### **id:** ID de la propiedad a obtener.
<br>

## POST /properties Crea una propiedad

### Authorization: para acceder a este recurso se requiere un Bearer token enviado en el header.
### Params

> #### Request Body
>> #### **direccion:** String

>> #### **habitaciones:** Number

>> #### **banios:** Number

>> #### **patio:** Boolean

>> #### **tipo_contrato:** String

>> #### **moneda:** String

>> #### **precio:** Number

>> #### **tipo:** Number

<br>

## PUT /properties/{id} Modifica una propiedad por Id
### Authorization: para acceder a este recurso se requiere un Bearer token enviado en el header.

### Params
> #### {Path}
>> #### **id:** ID de la propiedad a modificar.

> #### Request Body
>> #### **direccion:** String

>> #### **habitaciones:** Number

>> #### **banios:** Number

>> #### **patio:** Boolean

>> #### **tipo_contrato:** String

>> #### **moneda:** String

>> #### **precio:** Number

>> #### **tipo:** Number
<br>

## DELETE /properties/{id} Elimina una propiedad por ID

### Authorization: para acceder a este recurso se requiere un Bearer token enviado en el header. 

### Params

> #### {Path}
>> #### **id:** ID de la propiedad a eliminar.
<br>

## GET /users/token Genera un token

### Header

>> #### Autorizacion basica por usuario y contraseña

