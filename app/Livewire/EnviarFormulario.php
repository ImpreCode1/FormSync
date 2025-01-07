<?php

namespace App\Livewire;

use App\Models\Documento;
use App\Models\Financiera;
use App\Models\Infonegocio;
use App\Models\Informacion;
use App\Models\Marca;
use App\Models\Pago;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithFileUploads;

class EnviarFormulario extends Component
{
    public $currentStep = 1;

    use WithFileUploads;

    public $hasAdvancePayment = null;
    public $advancePaymentPercentage = null;
    public $advancePaymentDate = null;

    public $negocio;
    public $nombre;
    public $correo;
    public $numero;
    public $crm;
    public $fecha;
    public $oc;
    public $precio;
    public $cotizacion;
    public $soluciones;
    public $linea;
    public $codlinea;
    public $nomgerente;
    public $telgerente;
    public $corgerente;
    public $director;
    public $tel2gerente;
    public $cor2gerente;
    public $entregacliente;
    public $lugarentrega;
    public $espais;
    public $tiempoentrega;
    public $terminoentrega;
    public $tipoicoterm;
    public $prestar;
    public $suministrar;
    public $inicio;
    public $finalizacion;
    public $details;
    public $aplicagarantia;
    public $terminogarantia;
    // public $garantia;
    public $formapago;
    public $moneda;
    public $incluye_iva;
    // public $fecha_pago;
    public $clientcode;
    public $clientname;
    public $mail;
    public $otros;

    public $other;
    public $actualpago;
    public $monedaactual;
    public $porcentaje;
    public $aplicapoliza;
    public $fecha_pago;

    public $tipodocumento;
    public $rutadocumento;
    public $fechasubida;

    public $attachments = [];

    public $marca_id;
    public $archivos = [];
    public $documentosGuardados = [];


    public $files = [];
    public $dragging = false;

    protected $rules = [
        // Infonegocio
        'negocio' => 'required|numeric|unique:infonegocio,codigo_cliente',
        'nombre' => 'required|string|min:2',
        'correo' => 'required|email',
        'numero' => 'required|numeric',
        'crm' => 'required|numeric',

        // Marca
        'fecha' => 'required|date',
        'oc' => 'required|string|min:2',
        'precio' => 'required|string|min:2',
        'cotizacion' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        'soluciones' => 'required|string|min:2',
        'linea' => 'required|string|min:2',
        'codlinea' => 'required|string|min:2',
        'nomgerente' => 'required|string|min:2',
        'telgerente' => 'required|numeric',
        'corgerente' => 'required|email',
        'director' => 'required|string|min:2',
        'tel2gerente' => 'required|numeric',
        'cor2gerente' => 'required|email',

        // Campos opcionales en Marca
        'clientcode' => 'nullable|string|min:2',
        'clientname' => 'nullable|numeric',
        'mail' => 'nullable|email',

        // Información
        'entregacliente' => 'required|string|min:2',
        'lugarentrega' => 'required|string|min:2',
        'espais' => 'required|string|min:2',
        'tiempoentrega' => 'required|string|min:2',
        'terminoentrega' => 'required|date',
        'tipoicoterm' => 'required|string|min:2',
        'prestar' => 'required|string|min:2',
        'suministrar' => 'required|string|min:2',
        'inicio' => 'required|date',
        'finalizacion' => 'required|date',

        // Producto
        'details' => 'required|string|min:2',
        'aplicagarantia' => 'required|string|min:2',
        'terminogarantia' => 'required|string|min:2',
        'aplicapoliza' => 'required|string|min:2',
        'porcentaje' => 'required|numeric|min:0|max:100',

        // Condiciones de Pago
        'formapago' => 'required|string',
        'moneda' => 'required|string',
        'incluye_iva' => 'required|boolean',
        'fecha_pago' => 'required|date',

        'archivos.*' => 'file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
    ];

    protected $messages = [
        // Mensajes generales de validación
        'required' => 'El campo :attribute es obligatorio.',
        'string' => 'El campo :attribute debe ser texto.',
        'numeric' => 'El campo :attribute debe ser un número.',
        'email' => 'El campo :attribute debe ser una dirección de correo válida.',
        'date' => 'El campo :attribute debe ser una fecha válida.',
        'min' => [
            'string' => 'El campo :attribute debe tener al menos :min caracteres.',
            'numeric' => 'El campo :attribute debe ser al menos :min.',
        ],
        'max' => [
            'numeric' => 'El campo :attribute no puede ser mayor que :max.',
            'file' => 'El archivo :attribute no debe pesar más de :max kilobytes.',
        ],
        'file' => 'El campo :attribute debe ser un archivo.',
        'mimes' => 'El archivo :attribute debe ser de tipo: :values.',
        'unique' => 'El valor del campo :attribute ya está en uso.',

        // Infonegocio
        'negocio.required' => 'El código del cliente es obligatorio.',
        'negocio.numeric' => 'El código del cliente debe ser un número.',
        'negocio.unique' => 'El código del cliente ya está registrado.',
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
        'correo.required' => 'El correo electrónico es obligatorio.',
        'correo.email' => 'El correo electrónico debe ser válido.',
        'numero.required' => 'El número de teléfono es obligatorio.',
        'numero.numeric' => 'El número de teléfono debe contener solo números.',
        'crm.required' => 'El número CRM es obligatorio.',
        'crm.numeric' => 'El número CRM debe ser numérico.',

        // Marca
        'fecha.required' => 'La fecha es obligatoria.',
        'fecha.date' => 'La fecha debe tener un formato válido.',
        'oc.required' => 'La orden de compra es obligatoria.',
        'oc.min' => 'La orden de compra debe tener al menos 2 caracteres.',
        'precio.required' => 'El precio es obligatorio.',
        'precio.min' => 'El precio debe tener al menos 2 caracteres.',
        'cotizacion.file' => 'La cotización debe ser un archivo.',
        'cotizacion.mimes' => 'La cotización debe ser un archivo PDF, Word o Excel.',
        'cotizacion.max' => 'La cotización no debe pesar más de 10MB.',
        'soluciones.required' => 'Las soluciones son obligatorias.',
        'soluciones.min' => 'Las soluciones deben tener al menos 2 caracteres.',
        'linea.required' => 'La línea es obligatoria.',
        'linea.min' => 'La línea debe tener al menos 2 caracteres.',
        'codlinea.required' => 'El código de línea es obligatorio.',
        'codlinea.min' => 'El código de línea debe tener al menos 2 caracteres.',
        'nomgerente.required' => 'El nombre del gerente es obligatorio.',
        'nomgerente.min' => 'El nombre del gerente debe tener al menos 2 caracteres.',
        'telgerente.required' => 'El teléfono del gerente es obligatorio.',
        'telgerente.numeric' => 'El teléfono del gerente debe contener solo números.',
        'corgerente.required' => 'El correo del gerente es obligatorio.',
        'corgerente.email' => 'El correo del gerente debe ser válido.',
        'director.required' => 'El nombre del director es obligatorio.',
        'director.min' => 'El nombre del director debe tener al menos 2 caracteres.',
        'tel2gerente.required' => 'El teléfono del segundo gerente es obligatorio.',
        'tel2gerente.numeric' => 'El teléfono del segundo gerente debe contener solo números.',
        'cor2gerente.required' => 'El correo del segundo gerente es obligatorio.',
        'cor2gerente.email' => 'El correo del segundo gerente debe ser válido.',

        // Campos opcionales en Marca
        'clientcode.min' => 'El código de cliente debe tener al menos 2 caracteres.',
        'clientname.numeric' => 'El nombre del cliente debe ser numérico.',
        'mail.email' => 'El correo debe ser una dirección válida.',

        // Información
        'entregacliente.required' => 'Debe especificar si realiza la entrega al cliente.',
        'entregacliente.min' => 'La especificación de entrega debe tener al menos 2 caracteres.',
        'lugarentrega.required' => 'El lugar de entrega es obligatorio.',
        'lugarentrega.min' => 'El lugar de entrega debe tener al menos 2 caracteres.',
        'espais.required' => 'El país es obligatorio.',
        'espais.min' => 'El país debe tener al menos 2 caracteres.',
        'tiempoentrega.required' => 'El tiempo de entrega es obligatorio.',
        'tiempoentrega.min' => 'El tiempo de entrega debe tener al menos 2 caracteres.',
        'terminoentrega.required' => 'El término de entrega es obligatorio.',
        'terminoentrega.date' => 'El término de entrega debe ser una fecha válida.',
        'tipoicoterm.required' => 'El tipo de Incoterm es obligatorio.',
        'tipoicoterm.min' => 'El tipo de Incoterm debe tener al menos 2 caracteres.',
        'prestar.required' => 'Debe especificar si se prestará el servicio.',
        'prestar.min' => 'La especificación de préstamo debe tener al menos 2 caracteres.',
        'suministrar.required' => 'Debe especificar si se suministrará el producto.',
        'suministrar.min' => 'La especificación de suministro debe tener al menos 2 caracteres.',
        'inicio.required' => 'La fecha de inicio es obligatoria.',
        'inicio.date' => 'La fecha de inicio debe ser válida.',
        'finalizacion.required' => 'La fecha de finalización es obligatoria.',
        'finalizacion.date' => 'La fecha de finalización debe ser válida.',

        // Producto
        'details.required' => 'Los detalles del producto son obligatorios.',
        'details.min' => 'Los detalles del producto deben tener al menos 2 caracteres.',
        'aplicagarantia.required' => 'Debe especificar si aplica garantía.',
        'aplicagarantia.min' => 'La especificación de garantía debe tener al menos 2 caracteres.',
        'terminogarantia.required' => 'El término de garantía es obligatorio.',
        'terminogarantia.min' => 'El término de garantía debe tener al menos 2 caracteres.',
        'aplicapoliza.required' => 'Debe especificar si aplica póliza.',
        'aplicapoliza.min' => 'La especificación de póliza debe tener al menos 2 caracteres.',
        'porcentaje.required' => 'El porcentaje es obligatorio.',
        'porcentaje.numeric' => 'El porcentaje debe ser un número.',
        'porcentaje.min' => 'El porcentaje no puede ser menor que 0.',
        'porcentaje.max' => 'El porcentaje no puede ser mayor que 100.',

        // Condiciones de Pago
        'formapago.required' => 'La forma de pago es obligatoria.',
        'formapago.string' => 'La forma de pago debe ser texto.',
        'moneda.required' => 'La moneda es obligatoria.',
        'moneda.string' => 'La moneda debe ser texto.',
        'incluye_iva.required' => 'Debe especificar si incluye IVA.',
        'incluye_iva.boolean' => 'El campo incluye IVA debe ser verdadero o falso.',
        'fecha_pago.required' => 'La fecha de pago es obligatoria.',
        'fecha_pago.date' => 'La fecha de pago debe ser una fecha válida.',


        //archivos
        'archivos.*.mimes' => 'Solo se permiten archivos PDF, DOC, DOCX, XLS, XLSX',
        'archivos.*.max' => 'El archivo no debe pesar más de 10MB',
    ];

    public function updatedHasAdvancePayment($value)
    {
        if ($value === 'si') {
            $this->rules['actualpago'] = 'required|date';
            $this->rules['monedaactual'] = 'required|string|min:3';
        } else {
            unset($this->rules['actualpago']);
            unset($this->rules['monedaactual']);
        }
    }

    public function changeStep($step)
    {
        $this->currentStep = $step;
    }

    public function setAdvancePayment($value)
    {
        $this->hasAdvancePayment = $value;

        if ($value === 'no') {
            $this->advancePaymentPercentage = null;
            $this->advancePaymentDate = null;
        }
    }

    public function submit()
    {
        $this->validate();

        // dd($this->attachments);
        // dd('Método submit llamado', $this->all());

        $infonegocio = Infonegocio::create([
            'codigo_cliente' => $this->negocio, // * no puede ser igual a otro y es numerico obligatorio
            'nombre' => $this->nombre, // obligatorio varchar
            'correo' => $this->correo, // tipo correo electronico obligatorio
            'numero_celular' => $this->numero, // tipo numerico obligatorio
            'n_oportunidad_crm' => $this->crm, // *  es numerico obligatorio
        ]);

        $marca = Marca::create([
            'infonegocio_id' => $infonegocio->id,
            'fecha' => $this->fecha, // tipo fecha obligatorio
            'n_oc' => $this->oc, // obligatorio varchar
            'precio_venta' => $this->precio, // obligatorio varchar
            'adjunto_cotizacion' => $this->cotizacion, // puede soportar archivos de tipo pdf,doc,docx,xls,xlsx y es nulo
            'tipo_contrato' => $this->soluciones, // obligatorio
            'linea' => $this->linea, // obligatorio
            'codigo_linea' => $this->codlinea, // obligatorio
            'nombre' => $this->nomgerente, // obligatorio
            'telefono' => $this->telgerente, //obligatorio
            'correo_electronico' => $this->corgerente, // obligatorio

            'otro' => $this->clientcode, //nulo varchar
            'cel' => $this->clientname, //nulo tipo numerico
            'email' => $this->mail, //nulo tipo email

            'director' => $this->director, // obligatorio vacrhar
            'numero' => $this->tel2gerente, // obligatorio numerico
            'correo_director' => $this->cor2gerente, // obligatorio tipo correo
        ]);

        $informacion = Informacion::create([
            'marcas_id' => $marca->id,
            'realiza_entrega_cliente' => $this->entregacliente, // obligatorio varchar
            'lugar_entrega' => $this->lugarentrega, // obligatorio varchar
            'pais' => $this->espais, // obligatorio varchar
            'tiempo_entrega' => $this->tiempoentrega, //obligatorio
            'fecha_inicio_termino' => $this->terminoentrega, // obligatorio
            'tipo_incoterms' => $this->tipoicoterm, // obligatorio
            'servicio_a_prestar' => $this->prestar, // obligatorio
            'frecuencia_suministro' => $this->suministrar, // obligatorio
            'fecha_inicio' => $this->inicio, // obligatorio
            'fecha_finalizacion' => $this->finalizacion, // obligatorio
        ]);

        Producto::create([
            'informacion_id' => $informacion->id,
            'detalles_equipos' => $this->details, // obligatorio
            'aplica_garantia' => $this->aplicagarantia, // obligatorio
            'termino_garantia' => $this->terminogarantia, // obligatorio
            'aplica_poliza' => $this->aplicapoliza, // obligatorio
            'porcentaje_poliza' => $this->porcentaje, // obligatorio tipo porcentaje sin acpetar valores negativos
        ]);

        Pago::create([
            'marcas_id' => $marca->id,
            'fecha_pago' => $this->fecha_pago,
            'incluye_iva' => $this->incluye_iva,
        ]);

        Financiera::create([
            'marcas_id' => $marca->id,
            'forma_pago' => $this->formapago,
            'moneda' => $this->moneda,
            // 'garantiascredit' => $this->garantia,
            // 'existencia_anticipo' => $this->hasAdvancePayment ? 1 : 0,
            // 'porcentaje' => $this->anticipo,
            // 'fecha_pago' => $this->fecha_pago,
            'otros' => $this->otros,
        ]);

        $this->reset();

        // Documento::create([
        //     'marca_id' => $marca->id,
        //     'tipo_documento' => $this->tipodocumento,
        //     'ruta_documento' => $this->rutadocumento,
        //     'fecha_subida' => date('Y-m-d H:i:s'),
        // ]);

        $this->reset();

        session()->flash('message', 'Formulario enviado correctamente');

        // return redirect()->to('/enviar-formulario');
    }

    public function mount()
    {
        $this->files = [];
    }

    public function updatedAttachments()
    {
        $this->updateFilesList();
    }

    public function handleDrop($files)
    {
        // dd($files);
        foreach ($files as $file) {
            $this->attachments[] = $file;
        }

        $this->updateFilesList();
    }

    public function updateFilesList()
    {
        $this->files = [];
        foreach ($this->attachments as $attachment) {
            if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    $this->files[] = [
                        'name' => $file->getClientOriginalName(),
                        'size' => round($file->getSize() / 1024, 2),
                    ];
                }
            } else {
                $this->files[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'size' => round($attachment->getSize() / 1024, 2),
                ];
            }
        }
    }

    public function removeFile($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
        $this->updateFilesList();
    }

    public function dragOver()
    {
        $this->dragging = true;
    }

    public function dragLeave()
    {
        $this->dragging = false;
    }

    public function render()
    {
        return view('livewire.enviar-formulario', [
            'currentStep' => $this->currentStep,
        ]);
    }
}
