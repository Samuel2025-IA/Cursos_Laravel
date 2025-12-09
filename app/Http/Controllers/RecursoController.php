<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recursos = Recurso::with('user')
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return view('recursos.index', compact('recursos'));
    }

    /**
     * Display the protocolos document.
     */
    public function protocolos()
    {
        // Secciones del documento de protocolos
        $secciones = [
            [
                'id' => 'presentacion',
                'titulo' => 'PRESENTACIÓN',
                'contenido' => 'La Iglesia como madre y maestra tiene la obligación de cuidar la integridad de sus miembros y de brindar entornos protectores a las personas, grupos y comunidades que en su nombre o bajo su responsabilidad se reúnen para llevar a cabo programas, proyectos o actividades de carácter humanitario, espiritual o social que promuevan la vida, la justicia social y el cuidado de la Casa Común. La Iglesia particular de Apartadó y sus relacionados eclesiásticos como las parroquias, la Fundación Educativa Isaías Duarte Cancino, Funadpas, El Secretariado Diocesano de Pastoral Social, la Comisión Vida, Justicia y Paz, Fundación Diocesana para la Salud, entre otros; por la variedad de los servicios que ofrecen y el variado número de personas que intervienen, generan una gran posibilidad de riesgos que es necesario evitar. Para lograrlo ofrece a toda persona que se vincula a las actividades diocesanas un conjunto de normas básicas que le ayudará a cuidarse, cuidar a los demás y proteger la institución de las posibles consecuencias que pueda traer la afectación a otras personas, ya por efecto o defecto del ejercicio laboral desempeñado, cuando éste no se hace conforme a las normas indicadas. Los protocolos contenidos en este folleto, editado gracias a ACNUR, serán leídos, asimilados y firmados por cada persona que sea contratada en la Diócesis de Apartadó y sus relacionados eclesiásticos para un servicio esporádico o para un trabajo de mayor permanencia; cada líder de proyecto tiene la responsabilidad de hacer seguimiento a su cumplimento. De esta manera la institución Diócesis fortalece su cultura de seguridad y responsabilidad social y pone en práctica su política de cero corrupciones, cero abusos, cero tolerancias y cero indiferencias con prácticas que puedan desdecir de la moralidad que ha caracterizado la acción de la Iglesia en favor de los más vulnerables.'
            ],
            [
                'id' => 'codigo-conducta',
                'titulo' => '1. CÓDIGO DE CONDUCTA INSTITUCIONAL',
                'subsecciones' => [
                    [
                        'titulo' => '1.1. Introducción',
                        'contenido' => 'El presente Código de Conducta especifica los compromisos y el comportamiento que se espera que todo el personal de la Diócesis de Apartadó cumpla en el desempeño de sus funciones. Este código prescribe los valores básicos y las prácticas institucionales de las organizaciones que conforman la Diócesis. Las personas que trabajan con la Diócesis son seres muy humanos, comprometidos con su fe, competentes en su oficio, idóneos, capaces de mantener relaciones cordiales con las personas y grupos humanos que atienden.'
                    ],
                    [
                        'titulo' => '1.2. Componentes del código',
                        'contenido' => 'El código contiene una serie de obligaciones para el personal de la diócesis, distribuida en cinco áreas principales, a fin de facilitar la consulta:'
                    ],
                    [
                        'titulo' => '1.2.1. Valores, conducta y ética',
                        'contenido' => '• Entender y respetar los valores sociales, morales y los principios de la DSI.
• Mantiene un alto estándar en su conducta personal y profesional.
• Actúa siempre de buena fe, creando un entorno de respeto a la persona humana y a sus derechos.
• Denuncia todo atropello, explotación, discriminación, abuso de las personas con las cuales está relacionada.
• Evita el proselitismo religioso y aceptar las creencias de los demás.
• Evita cometarios de carácter racista, sexista, político que pueda ofender a otras personas, en público o en privado.
• Observa la legislación colombiana y la de aquellos países con los cuales entra en relación.
• Valora y respeta la cultura y costumbres locales y actúa de conformidad con ellas.
• Asegura el cumplimiento de la pautas de seguridad, salud y protección en el trabajo, para sí y para las otras personas.
• Maneja la confidencialidad de la información y conocimientos adquiridos durante el ejercicio de las labores.
• Establece relaciones de solidaridad, cooperación y comunión con otras personas y entidades.'
                    ],
                    [
                        'titulo' => '1.2.2. Conflictos de interés, coacción y corrupción',
                        'contenido' => '• Evita aprovechar sus funciones para el tráfico de influencias, ejercer presión y obtener favores personales o para otros.
• Manifiesta al superior inmediato y por escrito cualquier conflicto de intereses existente, potencial en cuestiones relacionadas con su trabajo o con cualquier dependencia de la diócesis. El superior manejará esta información en una base de datos.
• Se abstiene de aceptar favores, sobornos o cualquier otra forma de beneficios de personas relacionadas con su trabajo y bajo ninguna circunstancia.'
                    ],
                    [
                        'titulo' => '1.2.3. Protección de la propiedad de la Diócesis y ejercer la custodia',
                        'contenido' => '• Asegura el buen uso de los recursos de la Diócesis y la propiedad intelectual, protegiéndolos de ocasiones de robo, malversación, fraude, daños, etc.
• Realiza la rendición de cuentas de su trabajo bajo altos estándares de honestidad e integridad.'
                    ],
                    [
                        'titulo' => '1.2.4. Conducta personal',
                        'contenido' => '• Asume la conducta propia de una institución que pertenece a la Iglesia católica, sin ánimo de lucro, a nombre de la cual actúa.
• Evita utilizar cualquier tipo de drogas, consumir alcohol, que pongan en detrimento o perjuicio las actividades que realiza.
• Separa lo afectivo de lo laboral, evitando toda relación de tipo sexual con sus compañeros de trabajo o los beneficiarios del mismo.
• Acepta pequeños obsequios de valor simbólico, propios de la tradición local de hospitalidad, pero informándolos a el superior, que llevará el registro de los mismos.'
                    ],
                    [
                        'titulo' => '1.2.5. Abuso y explotación',
                        'contenido' => 'La Diócesis y sus diversas dependencias mantienen un entorno que promueve los valores y principios fundamentales emanados de la dignidad de la persona humana, en todas las relaciones que adelante, siendo sus compromisos mínimos:

• Evita el abuso y la explotación de cualquier empleado o beneficiario.
• Evita sacar provecho personal de la condición de indefensión, precariedad o limitación de cualquier empelado o beneficiario.
• Propicia un ambiente de relaciones sociales armoniosas basadas en el respeto mutuo, la comprensión, el trabajo de equipo, el servicio y el comportamiento ético.

La Diócesis y sus diversas dependencias condenan firmemente y prohíben todas las formas de abuso y explotación. Por lo tanto:

• Todo empleado a quien se compruebe actos graves de abuso y explotación, será despedido unilateralmente por falta grave de mala conducta. Para personas no subordinadas, el abuso o maltrato puede ser motivo para dar por terminada toda relación contractual.
• Queda prohibido todo intercambio de dinero, empleo, servicios, bienes a cambio de favores sexuales u otras formas humillantes de explotación, tanto entre empleados subordinados o entre un subordinado y un beneficiado.
• Toda sospecha de abuso o explotación dentro del equipo de diócesis o hacia alguno de sus beneficiarios, debe ser informado siguiendo los procedimientos propios de la Diócesis para estos casos.'
                    ],
                    [
                        'titulo' => '1.2.6. Implementación del código de conducta',
                        'contenido' => 'a. El código de conducta será aprobado por el obispo como representante legal de la Diócesis.
b. El presente código de conducta será incluido en el Marco Normativo mínimo de la Diócesis.
c. El código de conducta será publicado en los diversos espacios instituidos por la diócesis para brindar información al personal interno y al público en general.
d. A toda persona vinculada con la diócesis se le hará firmar el código de conducta al momento de su inducción y contratación.
e. El código de conducta es aplicable y vinculante para todos los empleados de la Diócesis, los consultores, voluntarios, pasantes, etc.
f. El mecanismo de denuncia de cualquier anomalía requiere un procedimiento que inicia con la presentación por escrito de la denuncia o queja a la persona responsable de Recursos Humanos.
g. Toda persona sin excepción alguna tiene el deber de informar cualquier sospecha, inquietud, duda relativa a las infracciones de este código. Ver procedimiento.
h. Toda denuncia se tratará de forma confidencial. Cualquiera que plantee inquietudes en cuanto a negligencia profesional grave será protegido(a) contra represalias o cualquier otro trato perjudicial si plantea serias preocupaciones, siempre y cuando las mismas se planteen de buena fe. Acusaciones deliberadamente falsas son una falta disciplinaria grave y se investigarán y tratarán como corresponda.
i. Cualquier infracción al Código de Conducta que se denuncie será investigada y podrá resultar en medidas laborales, y de constituir infracción a la ley, deberá ponerse en conocimiento de las autoridades locales, previo informe al Director de la dependencia a que pertenezca la persona implicada.
j. El Director o su Delegado decidirá sobre la apertura de una investigación que: sea objetiva e imparcial (se presume que los empleados son inocentes hasta que se demuestre su culpabilidad); completa pero oportuna (garantizará a las víctimas ser informadas sobre sus derechos y oportunidades de asesoramiento); no "victimizar a la víctima"; y proteger a los empleados y personal no subordinado de posibles represalias.'
                    ]
                ]
            ],
            [
                'id' => 'codigo-etica',
                'titulo' => '2. CÓDIGO DE ÉTICA',
                'subsecciones' => [
                    [
                        'titulo' => '2.1. Introducción',
                        'contenido' => 'La Diócesis de Apartadó desarrolla su misión y su actividad evangelizadora basada en la Sagrada Escritura, la Tradición y el Magisterio de la Iglesia Católica. Para la toma de decisiones se rige por el Código de Derecho Canónico (CIC). Las entidades diocesanas (fundaciones, delegaciones de pastoral, dependencias administrativas internas) se rigen por el derecho universal y diocesano y por las directrices de la CEC. La Diócesis y sus entidades satélites deben cumplir con estándares mínimos en materia de gobernanza, infraestructura organizacional, viabilidad financiera, rendición de cuentas y conducta ética. El presente Código de Ética se fundamenta en los valores y principios de la Diócesis, plasmados en su plan estratégico, y busca que todos sus miembros y personal los encarnen. El código describe valores, políticas y comportamientos específicos para los miembros de la Diócesis de Apartadó, así como procedimientos y criterios de evaluación. También incorpora principios orientadores de Caritas International, aplicando elementos relevantes a los programas que se desarrollen en alianza con Caritas nacional o internacional.'
                    ],
                    [
                        'titulo' => '2.2. Valores y principios',
                        'contenido' => 'El principio rector que inspira todo comportamiento ético es el la dignidad de la persona humana "toda vida humana es sagrada desde la concepción hasta su muerte natural, su dignidad y la naturaleza social del individuo, son el cimiento y la inspiración de una visión moral de la sociedad que se logrará en la medida que se vivan los siguientes valores y principios:'
                    ],
                    [
                        'titulo' => '2.2.1 Valores',
                        'contenido' => 'a. La justicia: Compromiso de construir un orden moral justo, comenzando por relaciones correctas a nivel personal, organizacional, comunitario y global, con un enfoque en la justicia social para los más desfavorecidos.

b. El Bien Común: La institución diocesana y su personal son responsables de trabajar hacia el Bien Común, asegurando el desarrollo individual y el orden social.

c. El desarrollo humano integral: Objetivo de promover el desarrollo integral de todas las personas y la comunidad, fomentando el crecimiento armónico de las personas y su entorno.

d. La compasión: Aspecto fundamental de la identidad diocesana, que implica aliviar el sufrimiento humano a través del apoyo psicosocial, la ayuda humanitaria imparcial, y siguiendo el ejemplo del Buen Samaritano.

e. La opción preferencial por los pobres y los oprimidos: Acompañar a los pobres, marginados y oprimidos, y a todas las personas y comunidades vulnerables, trabajando por la equidad en el uso de recursos y la autonomía social, viendo las situaciones de pobreza como oportunidades para un "futuro nuevo y más humano".

f. El respeto: Toda persona es importante en sí misma y por lo tanto merece todo respeto aquello que le es propio: su lengua, tradición religiosa, cultura, costumbres, origen, profesión, forma de pensar, etc.

g. Solidaridad: Entre los valores sociales la solidaridad es y será siempre un fin a alcanzar, promover, fortalecer y sostener a través de todo trabajo en pro de las personas y los pueblos.'
                    ],
                    [
                        'titulo' => '2.2.2. Principios',
                        'contenido' => 'a. Colaboración: La Diócesis de Apartadó establece puentes de colaboración con las instituciones que apoyan su objetivo misional y asume este principio como un compromiso de largo aliento en el acompañamiento de los distintos momentos del acto de la planeación, organización, ejecución, evaluación. Toda colaboración con otras entidades supone el respeto por los objetivos misionales del partner y la entrega conjunta de resultados.

b. Subsidiariedad: Toda labor con la comunidad buscará ante todo empoderar a las personas, grupos humanos y comunidades de su propio desarrollo, comenzando por lo bajo, maximizando las propias capacidades y recursos locales.

c. Participación: Propiciar la participación de todos los miembros de las comunidades a los cuales se sirven, asegurando su presencia en todo el proceso de diseño, gestión, implementación y evaluación de los proyectos y en la toma de decisiones relativas a los mismos desde el principio hasta el fin de la iniciativa.

d. Empoderamiento: Promover comunidades locales activas, capaces de entablar relaciones de respeto mutuo, de promover su propio desarrollo y ejercer un liderazgo social importante.

e. Independencia: La Diócesis y sus satélites mantienen su libertad frente a ideologías, posturas políticas, intereses económicos, favoritismos, etc., manteniendo su fidelidad a los postulados de la DSI.

f. Buena administración y rendición de cuentas: La gestión transparente y la rendición de cuentas de los recursos proporcionados por entidades estatales, la Iglesia, ONG, etc., asegurando que sean administrados por los organismos diocesanos, con informes rigurosos presentados a tiempo. Los recursos no deben ser desviados en beneficio de instituciones intermedias. (Cfr. Benedicto XVI, Caritas in Veritate, 47)

g. Igualdad: El trato equitativo para todas las personas es un principio para toda entidad diocesana, adhiriéndose a la universalidad, imparcialidad y apertura a todas las personas, especialmente a las más pobres y vulnerables, independientemente de su raza, sexo, etnia, credo, pensamiento político, etc.

h. Protección: Todas las personas deben cumplir con los estándares de salud y seguridad en el trabajo y las prácticas definidas por las organizaciones pertinentes. Las comunidades deben ser informadas de sus derechos de protección de datos personales cuando se requiera información de imagen.

i. Economías locales: Promover el uso de recursos y productos locales siempre que sea posible, apoyando la economía local siempre que no agote los suministros o cause inflación del mercado.

j. Cuidado de la Casa Común: Fomentar la promoción de una relación correcta con la creación, iniciativas de mejora ambiental, y la implementación de una cultura de ecología integral en todos los programas diocesanos.

k. Coordinación: Todas las actividades de ayuda que involucren entidades gubernamentales, eclesiales, de la sociedad civil y otras, deben estar debidamente coordinadas entre las partes, siguiendo los protocolos de cada entidad y los criterios de la entidad puente.

l. Incidencia: Todas las actividades humanitarias que beneficien a poblaciones vulnerables deben demostrar su impacto a nivel local, nacional o internacional, sirviendo como instrumento para el cambio y la transformación social.'
                    ]
                ]
            ],
            [
                'id' => 'conflicto-intereses',
                'titulo' => '3. CONFLICTO DE INTERESES',
                'subsecciones' => [
                    [
                        'titulo' => '3.1. Introducción y objetivo',
                        'contenido' => 'El objetivo de esta política es protegerle a usted y a la Diócesis de la deshonestidad o de cualquier apariencia de comportamiento inapropiado.

Este procedimiento se aplica a todos los colaboradores, contratistas, socios y voluntarios, de las diferentes dependencias de la Diócesis de Apartadó. El Obispo, directores y delegados están obligados legalmente a actuar en beneficio de la Diócesis y de acuerdo con su reglamento internos, misión y visión.'
                    ],
                    [
                        'titulo' => '3.2. Los conflictos de Intereses',
                        'contenido' => 'Los conflictos de intereses se pueden presentar cuando por prebendas personales o familiares, hay preferencias que puedan afectar la imagen de la institución.

Estas situaciones pueden limitar la libertad de expresión que enrarecen el mejor clima laboral, "es mejor callar por lo que pueda pasar", afectando el proceder transparente de la institución o generar un comportamiento inadecuado.

Puede afectar a la buena imagen de la Diócesis y/o sus dependencias y dar como resultado, decisiones o acciones contra los intereses institucionales que son los que deben prevalecer. Se invita a la honradez y a evitar todo comportamiento inadecuado que genere dudas o sospechas sobre las actuaciones: "ser y parecer".'
                    ],
                    [
                        'titulo' => '3.3. Como evitar los Conflictos de Intereses',
                        'contenido' => 'Para que prevalezcan los intereses de la institución sobre los intereses personales de sus empleados o colaboradores, se establecen las reglas sobre la manera de evitar o resolver tales conflictos:

• Quien se encuentre ante un conflicto de intereses o tenga conocimiento de esta situación, debe notificar por escrito a su superior con carácter privado.
• No se debe aceptar regalos o trato preferencial, si se sabe o se sospecha que por ello se va a obtener una ventaja de tipo comercial.
• No se amenazará o tomará represalias contra un empleado que se haya negado a cometer un acto de soborno o que se haya mostrado preocupado por estas normas.
• Se debe notificar a la dirección si se detecta un posible conflicto de interés y de no hacerlo, se puede incurrir en complicidad y cargar con las medidas disciplinarias que pueden terminar en un despido forzado.
• No se debe aprobar ninguna transacción de recursos que esté relacionada con intereses particulares y hay que poner todos los medios materiales a disposición de quien tenga competencias para actuar consecuentemente.
• Los procesos para la selección de proveedores, contratistas, y otro personal serán competitivos, justos, imparciales, abiertos y transparentes.
• Comisiones, honorarios, obsequios y atenciones: Es responsabilidad del director y del equipo administrativo velar para que no surjan conflictos de intereses con socios y proveedores. Es importante el cuidado en el manejo de la información y de los procedimientos establecidos. Una persona vinculada nunca aceptará regalos de proveedores o de clientes reales o potenciales. Estas normas prohíben cualquier aliciente que da como resultado la ganancia personal, o de la entidad asociada.
• Contratación de familiares: no se permite contratar laboralmente a familiares si:
  - El miembro del equipo de dirección participa en la decisión de contratar al familiar.
  - Se va a tener una relación de supervisión, subordinación o control con su familiar.
  - Se tiene acceso a información privilegiada para asuntos personales.'
                    ],
                    [
                        'titulo' => '3.4. Responsabilidad de directores y delegados',
                        'contenido' => 'Es responsabilidad del director velar por la aplicación de las reglas que previenen los conflictos de intereses y tramitar los casos relacionados.

El documento que contiene estas reglas de conflictos de intereses debe ser firmado por el director de la institución, el equipo de dirección o administrativo y todo el personal vinculado a la institución (contratistas, voluntarios o quienes tengan contrato laboral). También se menciona que al momento de la incorporación, las personas deben leer y firmar este documento.'
                    ]
                ]
            ],
            [
                'id' => 'acoso-laboral',
                'titulo' => '4. NORMAS CONTRA EL ACOSO LABORAL',
                'subsecciones' => [
                    [
                        'titulo' => 'Introducción',
                        'contenido' => 'La Diócesis de Apartadó y sus dependencias, en su visión de actualizar y ajustar sus protocolos del 2020, aceptan y se comprometen a trabajar contra el acoso en todas sus formas. Estas disposiciones están alineadas con los marcos legales colombianos y son obligatorias para todo el personal contratado por la institución, voluntarios, consultores y contratistas.'
                    ],
                    [
                        'titulo' => 'Objetivo',
                        'contenido' => 'La Diócesis de Apartadó y sus dependencias se comprometen a ofrecer un ambiente de trabajo profesional libre de intimidación, hostilidad, humillación, abuso u otras ofensas que puedan interferir con el desempeño laboral o la dignidad de una persona. Se establece explícitamente que el acoso en cualquiera de sus formas – verbal, físico, visual – no será tolerado. Esto incluye, entre otros, el acoso basado en raza, color, religión, ideas religiosas, filosóficas o políticas, sexo, edad, orígenes nacionales o ancestrales, discapacidad, condición médica, estado civil, o cualquier otro estatus legalmente protegido. También se prohíbe el acoso de colegas, compañeros y personas con las que se trabaja.'
                    ],
                    [
                        'titulo' => 'Alcance',
                        'contenido' => 'Esta política se aplica a todo el personal y los asociados de la Diócesis de Apartadó y sus dependencias.

Nota: El término "personal" hace referencia al personal, los voluntarios, las personas en práctica y los miembros de los órganos directivas. El término "asociados" se refiere a los consultores y los contratistas.'
                    ],
                    [
                        'titulo' => 'Definición y modalidades de acoso laboral',
                        'contenido' => 'Se entiende por acoso laboral "toda conducta persistente y demostrable, ejercida sobre un empleado, trabajador por parte de un empleador, un jefe o superior jerárquico inmediato o mediato, un compañero de trabajo o un subalterno, encaminada a infundir miedo, intimidación, terror y angustia, a causar perjuicio laboral, generar desmotivación en el trabajo, o inducir la renuncia del mismo".

El ordenamiento jurídico colombiano consagra las diferentes modalidades de acoso laboral, tipificadas en la Ley 1010 de 2006 "Por medio de la cual se adoptan medidas para prevenir, corregir y sancionar el acoso laboral y otros hostigamientos en el marco de las relaciones de trabajo":'
                    ],
                    [
                        'titulo' => '1. Maltrato laboral',
                        'contenido' => 'Todo acto de violencia contra la integridad física o moral, la libertad física o sexual y los bienes de quien se desempeñe como empleado o trabajador; toda expresión verbal injuriosa o ultrajante que lesione la integridad moral o los derechos a la intimidad y al buen nombre de quienes participen en una relación de trabajo de tipo laboral o todo comportamiento tendiente a menoscabar la autoestima y la dignidad de quien participe en una relación de trabajo de tipo laboral.'
                    ],
                    [
                        'titulo' => '2. Persecución laboral',
                        'contenido' => 'Toda conducta cuyas características de reiteración o evidente arbitrariedad permitan inferir el propósito de inducir la renuncia de las personas, mediante la descalificación, la carga excesiva de trabajo y cambios permanentes de horario que puedan producir desmotivación laboral.'
                    ],
                    [
                        'titulo' => '3. Discriminación laboral',
                        'contenido' => 'Todo trato diferenciado por razones de raza, género, edad, origen familiar o nacional, credo religioso, preferencia política o situación social que carezca de toda razonabilidad desde el punto de vista laboral.'
                    ],
                    [
                        'titulo' => '4. Entorpecimiento laboral',
                        'contenido' => 'Toda acción tendiente a obstaculizar el cumplimiento de la labor o hacerla más gravosa o retardarla con perjuicio para el trabajador o empleado.

Constituyen acciones de entorpecimiento laboral, entre otras, la privación, ocultación o inutilización de los insumos, documentos o instrumentos para la labor, la destrucción o pérdida de información, el ocultamiento de correspondencia o mensajes electrónicos.'
                    ],
                    [
                        'titulo' => '5. Inequidad laboral',
                        'contenido' => 'Asignación de funciones a menosprecio del trabajador.'
                    ],
                    [
                        'titulo' => '6. Desprotección laboral',
                        'contenido' => 'Toda conducta tendiente a poner en riesgo la integridad y la seguridad del trabajador mediante órdenes o asignación de funciones sin el cumplimiento de los requisitos mínimos de protección y seguridad para el trabajador.'
                    ],
                    [
                        'titulo' => 'Circunstancias agravantes',
                        'contenido' => 'De igual forma, la Ley define como circunstancias agravantes del hecho:

a) Reiteración de la conducta.
b) Cuando exista concurrencia de causales.
c) Realizar la conducta por motivo abyecto (acciones ultrajantes o dañinas), fútil (que carece de importancia o interés por su falta de fundamento) o mediante precio, recompensa o promesa remuneratoria.
d) Mediante ocultamiento, o aprovechando las condiciones de tiempo, modo y lugar, que dificulten la defensa del ofendido, o la identificación del autor partícipe.
e) Aumentar deliberada e inhumanamente el daño psíquico y biológico causado al sujeto pasivo.
f) La posición predominante que el autor ocupe en la sociedad, por su cargo, rango económico, ilustración, poder, oficio o dignidad.
g) Ejecutar la conducta valiéndose de un tercero o de un inimputable.
h) Cuando en la conducta desplegada por el sujeto activo se causa un daño en la salud física o psíquica al sujeto pasivo.'
                    ],
                    [
                        'titulo' => 'Conductas que constituyen acoso laboral',
                        'contenido' => 'Por último, la Ley considera como conductas que constituyen acoso laboral:

i) Los actos de agresión física, independientemente de sus consecuencias.
j) Las expresiones injuriosas o ultrajantes sobre la persona, con utilización de palabras soeces o con alusión a la raza, el género, el origen familiar o nacional, la preferencia política o el estatus social.
k) Los comentarios hostiles y humillantes de descalificación profesional expresados en presencia de los compañeros de trabajo.
l) Las injustificadas amenazas de despido expresadas en presencia de los compañeros de trabajo.
m) Las múltiples denuncias disciplinarias de cualquiera de los sujetos activos del acoso, cuya temeridad quede demostrada por el resultado de los respectivos procesos disciplinarios.
n) La descalificación humillante y en presencia de los compañeros de trabajo de las propuestas u opiniones de trabajo.
o) Las burlas sobre la apariencia física o la forma de vestir, formuladas en público.
p) La alusión pública a hechos pertenecientes a la intimidad de la persona.
r) La imposición de deberes ostensiblemente extraños a las obligaciones laborales, las exigencias abiertamente desproporcionadas sobre el cumplimiento de la labor encomendada y el brusco cambio del lugar de trabajo o de la labor contratada sin ningún fundamento objetivo referente a la necesidad técnica de la empresa.
s) La exigencia de laborar en horarios excesivos respecto a la jornada laboral contratada o legalmente establecida, los cambios sorpresivos del turno laboral y la exigencia permanente de laborar en dominicales y días festivos sin ningún fundamento objetivo en las necesidades de la empresa, o en forma discriminatoria respecto a los demás trabajadores o empleados.
t) El trato notoriamente discriminatorio respecto a los demás empleados en cuanto al otorgamiento de derechos y prerrogativas laborales y la imposición de deberes laborales.
u) La negativa a suministrar materiales e información absolutamente indispensables para el cumplimiento de la labor.
v) La negativa claramente injustificada a otorgar permisos, licencias por enfermedad, licencias ordinarias y vacaciones, cuando se dan las condiciones legales, reglamentarias o convencionales para pedirlos.
w) El envío de anónimos, llamadas telefónicas y mensajes virtuales con contenido injurioso, ofensivo o intimidatorio o el sometimiento a una situación de aislamiento social.'
                    ],
                    [
                        'titulo' => 'Formas de acoso',
                        'contenido' => 'El acoso puede tomar muchas formas. Pueden ser, aunque no solo, de palabras, con letreros, chistes ofensivos, caricaturas, fotos, carteles, comentarios, bromas, intimidación, contactos, agresiones físicas o incluso violencia.

El acoso no es necesariamente sexual por naturaleza. También puede tomar la forma de otra actividad verbal, incluyendo comentarios despectivos que no estén dirigidos directamente a la persona en cuestión, pero que se produzcan en situaciones en las que dicha persona pueda oírlos.

Otra conducta prohibida son las medidas de represalias contra un empleado, por discutir o realizar una reclamación por acoso.

También va en contra de las políticas de la Diócesis de Apartadó y sus dependencias, descargar fotos, videos, audios o material inapropiado, a través de los sistemas informáticos.',
                        'imagen' => 'acoso-laboral1.png'
                    ],
                    [
                        'titulo' => '7. Acoso y Abuso Sexual',
                        'contenido' => ''
                    ],
                    [
                        'titulo' => '7.1. Definición legal',
                        'contenido' => 'El delito de acoso sexual está tipificado en el artículo 210A del Código Penal. Se entiende por acoso sexual:

"He quien, para su beneficio o el de un tercero, y valiéndose de su manifiesta superioridad o relaciones de autoridad o poder, edad, sexo, labor, social, familiar o económica, acose, persiga, hostigue o asedie, con fines sexuales no consentidos, a otra persona, incurrirá en prisión de uno (1) a tres (3) años."

El agresor y la víctima pueden ser cualquier persona, independientemente del género o identidad sexual. Para que se configure el delito, el agresor debe tener una relación de superioridad sobre la víctima, que le permita subyugarla, aterrorizarla, subordinarla, intimidarla, coaccionarla, agraviarla, humillarla o mortificarla.'
                    ],
                    [
                        'titulo' => '7.2. Formas de acoso sexual en el trabajo',
                        'contenido' => 'El acoso sexual en el lugar de trabajo puede ocurrir de dos formas:

1. Quid Pro Quo (Una cosa por otra): Se presenta cuando el acosador ofrece un beneficio laboral (por ejemplo, aumento de salario, ascenso, permanencia en el trabajo) a la víctima a condición de que acceda a comportamientos sexuales no deseados, irrazonables y ofensivos.

2. Ambiente laboral hostil: Se presenta cuando la conducta sexual crea un ambiente de trabajo intimidante, hostil u ofensivo para la víctima.'
                    ],
                    [
                        'titulo' => '7.3. Definición amplia de acoso sexual',
                        'contenido' => 'Puede incluir cualquier forma de conducta verbal, no verbal o física de naturaleza sexual que no sea deseada y que se adopte con el propósito o para causar el efecto de violar la dignidad de una determinada persona, contribuyendo a la creación de un entorno intimidante, hostil, degradante, humillante u ofensivo. Puede incluir insinuaciones sexuales desagradables, la solicitud de favores sexuales u otro tipo de contacto verbal o físico de naturaleza sexual. Es importante mencionar que el acoso sexual traspasa las fronteras de la edad y el género.'
                    ],
                    [
                        'titulo' => '7.4. Conductas agravantes',
                        'contenido' => 'Como conductas agravantes se indican las contenidas en la Ley 1236 de 2008, así:

Pornografía con menores: Está totalmente prohibido en los ámbitos laborales o privados, fotografías, filmaciones, venta o compra, exhibición o comercialización de material pornográfico en el que participen menores de edad.

O facilitación de medios de comunicación para ofrecer servicios sexuales de menores: El que utilice o facilite por correo, redes globales de información, o cualquier otro medio de comunicación para obtener contacto sexual con menores de 18 años, o para ofrecer servicios sexuales con éstos.

Cabe resaltar, que se tendrá en cuenta la edad estipulada por la Ley (18 años), sin importar las costumbres culturales de las personas que cometen el delito o de las que pueden ser víctimas.'
                    ],
                    [
                        'titulo' => '7.5. Responsabilidad',
                        'contenido' => 'Todos los empleados, y en particular los directivos, que hagan parte de la Diócesis de Apartadó y sus dependencias, tienen la responsabilidad de mantener el entorno laboral libre de cualquier tipo de acoso.

Cuando los órganos directivos sepan de un posible caso de acoso, están obligados por ley a adoptar cuanto antes las medidas oportunas, aunque la persona (o personas) afectada no quiera que la institución lo haga.

Se anima firmemente a cualquier persona que sepa de un caso de acoso, ya sea porque lo haya presenciado, porque se lo hayan contado o porque sea objeto del mismo, a denunciarlo mediante el Sistema de Quejas Reclamos, Sugerencias y Felicitaciones, que incluye el procedimiento para la tramitación de reclamaciones.'
                    ]
                ]
            ],
            [
                'id' => 'comite-convivencia',
                'titulo' => '5. COMITÉ DE CONVIVENCIA',
                'subsecciones' => [
                    [
                        'titulo' => 'Introducción',
                        'contenido' => 'La Diócesis de Apartadó y sus dependencias, tendrán un Comité de Convivencia, encargado de Prevenir el Acoso Laboral, contribuyendo a proteger a los empleados contra riesgos psicosociales que afecten la salud en los lugares de trabajo, siempre garantizando el debido proceso, consagrado en el artículo 29 de la Constitución Política de Colombia.'
                    ],
                    [
                        'titulo' => 'Sus principales funciones son',
                        'contenido' => '• Recibir y dar trámite a las quejas presentadas por escrito en las que se describan las situaciones que puedan constituir acoso laboral, así como las pruebas que las soportan.
• Citar individualmente a cada una de las partes involucradas en las quejas, con el fin de escuchar los hechos que dieron lugar a la misma.
• Citar conjuntamente a los trabajadores involucrados en las quejas con el fin de establecer compromisos de convivencia.
• Llevar el archivo de las quejas presentadas, la documentación soporte y velar por la reserva, custodia y confidencialidad de la información.
• Enviar las comunicaciones con las recomendaciones dadas por el Comité a las diferentes dependencias de la entidad.
• Citar a reuniones y solicitar los soportes requeridos para hacer seguimiento al cumplimiento de los compromisos adquiridos por cada una de las partes involucradas.
• Elaborar informes trimestrales sobre la gestión del Comité que incluya estadísticas de las quejas, seguimiento de los casos y recomendaciones, los cuales serán presentados a la dirección de la entidad.'
                    ]
                ]
            ],
            [
                'id' => 'denuncia-acoso',
                'titulo' => 'Denuncia de Acoso',
                'contenido' => 'El acoso a cualquier persona con la que trabajamos por parte de un empleado, un asociado o funcionario de la Diócesis de Apartadó y sus dependencias, debe ser inmediatamente denunciado. La Diócesis de Apartadó contará con un comité encargado de conocer y tramitar este tipo de denuncias, siempre garantizando el debido proceso, consagrado en el artículo 29 de la Constitución Política de Colombia.'
            ],
            [
                'id' => 'etica-medio-ambiental',
                'titulo' => '6. DIRECTRICES REFERENTES A LA ÉTICA MEDIO AMBIENTAL',
                'subsecciones' => [
                    [
                        'titulo' => 'Introducción',
                        'contenido' => 'El desafío urgente de proteger nuestra casa común incluye la preocupación de unir a toda la familia humana en la búsqueda de un desarrollo sostenible e integral, pues sabemos que las cosas pueden cambiar. El Creador no nos abandona, nunca hizo marcha atrás en su proyecto de amor, no se arrepiente de habernos creado. La humanidad aún posee la capacidad de colaborar para construir nuestra casa común.

Deseo reconocer, alentar y dar las gracias a todos los que, en los más variados sectores de la actividad humana, están trabajando para garantizar la protección de la casa que compartimos. Merecen una gratitud especial quienes luchan con vigor para resolver las consecuencias dramáticas de la degradación ambiental en las vidas de los más pobres del mundo. Los jóvenes nos reclaman un cambio. Ellos se preguntan cómo es posible que se pretenda construir un futuro mejor sin pensar en la crisis del ambiente y en los sufrimientos de los excluidos. Laudato Sí, 13.'
                    ],
                    [
                        'titulo' => 'Compromiso de la Diócesis',
                        'contenido' => 'La Diócesis de Apartadó, y sus diversas dependencias, se comprometen a cumplir con las Directrices de la Comisión de Ecología creada por el Dicasterio para el Desarrollo Humano Integral, instituido por el Santo Padre Francisco con la Epístola Apostólica del 17 agosto de 2016 para el cuidado del desarrollo humano integral con la atención puesta en los temas de la justicia, de la paz y de la custodia de la Creación.'
                    ],
                    [
                        'titulo' => 'Compromisos que orientan la implementación de Programas y Proyectos en los territorios',
                        'contenido' => '• Optamos por una pastoral rural y de la tierra verdaderamente evangelizadora, orgánica, profética que lleve a la práctica del amor, la paz y la justicia; que responda a las exigencias de la realidad actual del mundo rural; que, sin excluir a nadie, se dirija especialmente a la promoción social y al desarrollo integral de los hermanos campesinos más pobres y que cumpla con su misión primordial de acompañar a los hombres y mujeres del campo, sembrando la semilla del Evangelio, regándola permanentemente y buscando conjuntamente salidas y alternativas a una situación que requiere medidas urgentes y eficientes.

• Optamos por una pastoral rural que tenga en cuenta las diferentes dimensiones económica, social, política, ambiental, cultural y religiosa, cada una de ellas suficientemente definida e interrelacionada.

• Dentro de un contexto de espiritualidad de comunión desarrollaremos la Espiritualidad de la Creación.

• Promoveremos la reflexión bíblica y teológica desde la realidad rural en perspectiva transformadora y de esperanza cristianas.

• Apoyaremos las expresiones diocesanas y regionales que conduzcan al desarrollo integral y sostenible de la población campesina, indígena y afrodescendiente.

• Dedicaremos especial atención a la formación de animadores campesinos y grupos étnicos para el crecimiento integral de la comunidad. Igualmente, ofreceremos programas de formación para sacerdotes y seminaristas.

• Promoveremos experiencias de cultura ambiental y agroecológica desde el reconocimiento de la interculturalidad que caracteriza los habitantes de la región del Urabá y Chocó.

• Acompañaremos y animaremos procesos asociativos solidarios.

• Impulsaremos desde la iglesia las organizaciones campesinas y étnicas.'
                    ],
                    [
                        'titulo' => 'Los criterios que orientan la implementación de esta directriz son',
                        'contenido' => '1. Asumir nuestro trabajo como una opción de vida: Portar y promover el sentido de la reconciliación, el mensaje de fraternidad en todos los niveles de la sociedad, conducente al entendimiento y la integración cultural.

2. El respeto y promoción de la diversidad cultural: Asumir nuestro trabajo como un proceso que nos ayuda a entrar en otros mundos o culturas y a enriquecer el propio, mediante la reflexión y ruptura con las representaciones estereotipadas de la cultura hegemónica respecto otras identidades culturales.

3. Acompañamiento con trato humano: Que posibilite la construcción de vínculos vitales. Se trata de asumir también con el corazón la riqueza de la experiencia que se viva con la comunidad, que nos permita encauzar los sentimientos para el logro de los objetivos comunes.

4. Promover la participación de las comunidades y los grupos catalogados como minoritarios, en las políticas y estrategias de inclusión social.

5. Promover la implementación del enfoque de sostenibilidad ambiental en los programas y proyectos, que en los territorios implica:

Conservación de la biodiversidad y gestión sostenible de los recursos naturales: Es necesario asegurar que, si el programa/proyecto se desarrolla en alguna de las siguientes zonas, atienda el régimen de uso definido para ellas contenido en la Ley y demás actos administrativos, en Colombia: Áreas del Sistema de Parques Nacionales Naturales de Colombia, (Sistema Nacional de Áreas Protegidas/SINAP y Parques Regionales, Otras categorías integrantes del SINAP, Prospección para la declaración de áreas protegidas, Páramos, Bosque Seco Tropical, Humedales, Humedales RAMSAR, Manglares, Reservas de Biosfera, Reservas Forestales de Ley 2da de 1959, Áreas susceptibles de restauración, Áreas con presencia de grupos étnicos, Áreas con riesgo de deforestación, Áreas con portafolios de medidas de mitigación y adaptación al cambio climático.

Nota: Decreto 2372 de 2010, por el cual se reglamenta el Decreto Ley 2811 de 1974, Ley 99 de 1995, Ley 165 de 1994 y decreto Ley 216 de 2003; en relación con el Sistema Nacional de Áreas Protegidas, las categorías de manejo que lo conforman y se dictan otras disposiciones.

Convenio Ramsar protección de humedales, en su artículo número 1 del protocolo "define una zona húmeda o humedal como cualquier extensión de marisma, pantano o turbera, o superficie cubierta de aguas, sean estas de régimen natural o artificial, permanentes o temporales, estancadas o corrientes, dulces, salobres o saladas, incluidas las extensiones de aguas marinas cuya profundidad en marea baja no exceda de seis metros" (Ramsar, 1971).'
                    ],
                    [
                        'titulo' => 'Cambio Climático',
                        'contenido' => 'La Diócesis de Apartadó trabaja en el aumento de la resiliencia contra los riesgos relacionados con el cambio climático, especialmente en la Serranía del Abibe, serranía del Darién y el Bajo Atrato; de este trabajo se identifican algunas acciones que deben implementarse en cualquier Programa o Proyecto:

• Valorar si los programas/proyectos, pueden incidir en el cambio de las condiciones climáticas en las comunidades en los que se implementan.
• No promover emisiones de gases efecto invernadero.
• Los programas/proyectos no deben aumentar los riesgos o vulnerabilidades socio-ambientales relacionados con el cambio climático.
• No debe hacer uso de sustancias contaminantes.
• Se debe promover sistemas agrícolas o silvo-pastoriles sostenibles.

Por lo anterior adoptamos esta política ambiental que hace referencia a establecer y revisar los objetivos y metas medioambientales:'
                    ],
                    [
                        'titulo' => 'Objetivos y metas medioambientales',
                        'contenido' => '1. Aplicar los criterios ambientales en todos los procesos de planificación y toma de decisiones sobre cuestiones que pudieran afectar al medio ambiente.

2. Cumplir con la legislación ambiental que es de aplicación en nuestras actividades como Diócesis de Apartadó, así como con los compromisos adquiridos de forma voluntaria.

3. Implementar las herramientas necesarias para prevenir la contaminación.

4. Utilizar racionalmente los recursos, minimizando los consumos de agua, papel y energía, reduciendo la generación de residuos y emisiones, favoreciendo el reciclaje y buscando soluciones eco-eficientes.

5. Implicar a los trabajadores de la Diócesis de Apartadó en el logro de los objetivos ambientales propuestos mediante programas de formación y sensibilización.

6. Promover buenas prácticas ambientales motivando y haciendo participes a nuestros trabajadores, beneficiarios de los programas y grupos parroquiales, sensibilizándonos sobre nuestra responsabilidad ambiental.

7. Cooperar en la educación ambiental de nuestra comunidad y con aquellas instituciones y organizaciones que promuevan soluciones innovadoras para la protección del medio ambiental para protección de la creación.

8. Contribuir con nuestras actividades a la mejora de las condiciones medioambientales identificando puntos de mejora y trabajando en soluciones eficientes adaptadas a cada entorno.

9. Compromisos de mejora continua en todos aquellos aspectos ambientales de la Diócesis de Apartadó.'
                    ]
                ]
            ],
            [
                'id' => 'proteccion-ninos-adultos-vulnerables',
                'titulo' => '7. POLÍTICA Y PROCEDIMIENTOS DE LA PROTECCIÓN DE NIÑOS, NIÑAS, ADOLESCENTES Y ADULTOS VULNERABLES',
                'imagen' => 'imagen.png',
                'subsecciones' => [
                    [
                        'titulo' => '7.1. La importancia de protección de niños, niñas, adolescentes y adultos vulnerables',
                        'contenido' => 'La Iglesia está llamada a ser un "espacio protegido" para los derechos y la dignidad de las personas; esto incluye niños, jóvenes y adultos en riesgo. Desde la diócesis de Apartadó somos conscientes de la necesidad de hacer explícita y visible esta determinación: que las actividades y el trabajo de todas las personas vinculada a ella promuevan la seguridad y protección de los niños, jóvenes y adultos en riesgo, por lo que declaramos tolerancia cero al acoso, la intimidación, la explotación y los abusos sexuales.

Nota: Se ha redactado ajustando la política que en este sentido tiene la diócesis de Apartadó, con aportes de la política de Sciaf.

Todo adulto que tenga contacto habitual con menores de edad y con adultos vulnerables en su quehacer pastoral y/o actividades humanitarias debe conocer bien su papel, la función específica que se la ha confiado y conducirse en el trato con ellos de manera respetuosa, prudente y equilibrada.

Todos los niños y adultos en riesgo merecen la oportunidad de alcanzar la plenitud de su potencial. Para alcanzar esto, los niños y adultos vulnerables necesitan sentirse amados y valorados, y estar apoyados por una red confiable y relaciones afectivas, principalmente dentro de su grupo familiar. Si se les niega la oportunidad y el apoyo que necesitan para alcanzar estos resultados, los niños están en riesgo no solo de una niñez pobre, sino también de una desventaja y exclusión social en la etapa adulta. El abuso y la negligencia plantean problemas particulares.'
                    ],
                    [
                        'titulo' => '7.2. Finalidad',
                        'contenido' => 'Este Código de Buenas Prácticas tiene dos polos fundamentales:'
                    ],
                    [
                        'titulo' => '7.2.1. Cultivar estilos sanos de relaciones interpersonales',
                        'contenido' => 'La Iglesia se preocupa de generar ambientes donde priman modos de relación respetuosos de la dignidad de todas las personas de la comunidad. La Iglesia promueve un modo de relación que supone el respeto y reconocimiento del otro, adoptando medidas cuando se incurre en transgresión de los límites inherentes a toda relación pastoral. En este punto, la Iglesia está llamada a identificar señales de comportamiento que revelan la existencia de abuso de poder y manipulación de conciencia, tomando medidas cuando se incurre en dichas prácticas.'
                    ],
                    [
                        'titulo' => '7.2.2. Formar y capacitar para la prevención de situaciones abusivas',
                        'contenido' => 'Todos los responsables pastorales deben estar suficientemente entrenados tanto para la prevención de abusos de poder (manipulación de conciencia, abuso de autoridad, abuso sexual y abuso económico, entre otros), como para desarrollar y mantener actitudes y habilidades necesarias para proteger a todos quienes participan en la Iglesia, en especial a aquellos más vulnerables.'
                    ],
                    [
                        'titulo' => '7.3. Pautas positivas y límites que se deben tomar',
                        'imagen' => 'niño.jpg',
                        'contenido' => 'En el curso de sus actividades, los trabajadores pastorales deben:

7.3.1. Usar la prudencia y el respeto en relación con los menores y personas vulnerables. Llevar a cabo las muestras físicas de afecto con mesura y respeto, de manera que nunca puedan parecer desproporcionadas y respetar la integridad física del menor.

7.3.2. Proporcionar a los menores y personas vulnerables modelos de referencia positivos.

7.3.3. Ser siempre visibles para los demás en presencia de menores y personas vulnerables.

7.3.4. Informar a los responsables de cualquier comportamiento potencialmente peligroso.

7.3.5. Respetar la esfera de confidencialidad del menor y adulto vulnerable.

7.3.6. Informar a los padres o tutores de las actividades propuestas y los métodos organizativos relacionados. Es necesario pedir siempre su autorización firmada para salidas que supongan que los menores han de dormir fuera de casa, asegurando un número suficiente de acompañantes y organizando lo necesario para la diferenciación del alojamiento de los niños por sexo.

7.3.7. Usar la debida prudencia en la comunicación con los menores, también por teléfono y en las redes sociales.

7.3.8. Las actividades con menores se deben llevar a cabo en salas adecuadas para la edad y etapa de desarrollo de los menores. En la medida de lo posible, los trabajadores pastorales deben tener especial cuidado para asegurarse de que los menores no entren ni permanezcan en lugares ocultos a la vista o fuera de control.

7.3.9. Si un niño o joven comenta que está siendo, o ha sido abusado:
a) Escuchar y aceptar lo que el niño/joven dice, tomar las acusaciones en serio, permitirle hablar libremente sin presión y afirmar su decisión de revelar.
b) Informarle que la información debe ser reportada a otros, sin prometer confidencialidad completa.
c) No investigar, informar, cuestionar o confrontar al presunto abusador.
d) Discutir los siguientes pasos.
e) Registrar cuidadosamente lo que se escuchó mientras está fresco, incluyendo fecha, hora y cualquier otro detalle del incidente.
f) Mantener la confidencialidad y solo discutir la situación con el director del departamento correspondiente, a quien también se le entregará el registro escrito. Evitar cualquier demora en esta acción.

7.3.10. En caso de conocimiento fundado de conductas improcedentes (exhibicionismo, conversaciones impropias o relaciones personales inapropiadas) entre menores o adolescentes, se informará puntualmente a la familia que deberá hacerse cargo inmediatamente del menor.'
                    ]
                ]
            ],
            [
                'id' => 'prohibiciones-trabajadores-pastorales',
                'titulo' => '8. Está estrictamente PROHIBIDO para los trabajadores pastorales',
                'contenido' => '• Infligir castigos corporales de cualquier tipo. Dada esta prohibición, no puede justificarse en ningún caso contacto físico por este motivo.

• Establecer una relación preferencial con un menor de edad. Es motivo de cese inmediato de la actividad pastoral cualquier relación sentimental, consentida o no, de un adulto con un menor de edad.

• Dejar a un niño en una situación potencialmente peligrosa dada su situación mental o física.

• Recurrir a un menor de manera ofensiva o involucrarse en conductas inapropiadas o sexualmente sugestivas. Están absolutamente prohibidos juegos, bromas o castigos que puedan tener connotación sexual.

• Discriminar a un menor o un grupo de niños. Están totalmente prohibidas las novatadas y otras dinámicas y juegos que puedan llevar consigo actos vejatorios, denigrantes o sexistas.

• Pedir a un niño que guarde un secreto o darle regalos discriminando al resto del grupo.

• Fotografiar o grabar a un menor sin el consentimiento por escrito de sus padres o tutores.

• Publicar o difundir, a través de la red o la red social, imágenes que reconozcan a un niño de una manera reconocible sin el consentimiento de los padres o tutores.

• Ponerse en situación de riesgo o claramente ambigua: entrar en los vestuarios, mientras estén los menores, compartir habitación de hotel o tienda de campaña, o subir a un menor a solas en un coche.

• Quedarse a solas mucho tiempo con un menor. Cuando haya que hablar en privado con un menor, hacerlo en un entorno visible y accesible a los demás.',
                'subsecciones' => [
                    [
                        'titulo' => 'a) Para asegurar la concientización y prevención del abuso a menores y adultos vulnerables',
                        'contenido' => '• Se asegurará que voluntarios y personal contratado asistan a una formación anual con el fin de que sean conscientes del problema de los abusos para con la infancia y adultos vulnerables y los riesgos para los mismos, y para asegurar que la política de protección de niños, niñas y adultos vulnerables sea conocida y aplicada.

• En el programa de inducción para el personal nuevo y voluntarios que tengan contacto con niños, jóvenes y adultos vulnerables se incluirá una sesión de formación con el fin de que sean conscientes del problema de los abusos para con la infancia, adultos vulnerables y los riesgos para los mismos, y para asegurar que la política de protección de niños, niñas y adultos vulnerables sea conocida y aplicada.

• Previa a la incorporación de nuevos contratados o voluntarios, éstos se someterán a una verificación de antecedentes.

• Todo el personal y los voluntarios conocen el formato y la ruta para presentar inquietudes y/o denuncias y facilitarán que también las personas y comunidades con las que trabajan la conozcan.

• Se asegurará que voluntarios y personal contratado tengan acceso a una copia de la política de Protección de Niños y Adultos en Riesgo.

• Se requerirá que todo el personal y voluntarios manifieste con su firma que conoce y acata la presente Política y Procedimientos de la Protección de Niños, Niñas, Adolescentes y Adultos Vulnerables.

• Los directores de cada dependencia tienen la responsabilidad de asegurar que el personal a su cargo estén conscientes de la política, y de recordarles de sus responsabilidades cuando sea necesario. Los directores deben hacer parte en el entrenamiento de la protección de niños y adultos en riesgo.

• Cualquier persona, trabajador o voluntario, que planee eventos debe estar seguro que las cuestiones de protección de niños y adultos en riesgos sean tenidas en cuenta como parte del riesgo de análisis y/o las condiciones de seguridad.

• Esta política será revisada cuando se considere oportuno y al menos cada dos años.'
                    ]
                ]
            ],
            [
                'id' => 'implementacion',
                'titulo' => 'Implementación:',
                'contenido' => 'Corresponderá al Comité Diocesano de Convivencia, presidido por el obispo, la adopción, implementación, divulgación y evaluación periódica de las buenas prácticas a nivel diocesano.

El Comité de Convivencia será el encargado de velar por la protección de menores y personas vulnerables, y el cumplimento de estas buenas prácticas en todas las dependencias diocesanas.

Cualquier conducta inapropiada, incluso si no integra los detalles de un delito, debe abordarse con prontitud, con equilibrio, prudencia y delicadeza, informando de inmediato a los padres o tutores. El director o, en su caso, la propia diócesis, deben actuar siempre que las personas a su cargo vulneren o no sigan este Código de Buenas Prácticas.

El director de cada dependencia es el encargado de recibir las quejas y denuncias respecto al incumplimiento de esta política y las presenta al Comité de Convivencia, donde serán analizadas y se tomarán las medidas pertinentes según el caso.'
            ],
            [
                'id' => 'anexo-1',
                'titulo' => 'ANEXO 1',
                'titulo_principal' => 'DEFINICIÓN DEL ABUSO SEXUAL A MENORES Y PERSONAS VULNERABLES',
                'contenido' => 'Se entiende por maltrato a menores y a personas vulnerables cualquier conducta o actitud, directa o indirecta por parte de un adulto, otro menor o institución que implique la falta de atención y cuidados que un niño, adolescente o persona vulnerable necesita para crecer, desarrollarse y vivir emocional y físicamente de una forma óptima¹².

El maltrato puede darse por acción o por omisión-negligencia.',
                'nota' => '¹² Según la legislación colombiana (Ley 1098 de 2006, art. 3; y sin perjuicio de lo establecido en el Código Civil Colombiano -sancionado en la Ley 57 de 1887- en su artículo 34) "se entiende por niño o niña, las personas entre los 0 y los 12 años, y por adolescente, las personas entre 12 y 18 años de edad". Las personas en situación de vulnerabilidad, pueden ser también adultas y se entiende por tal situación la que corresponde a condiciones determinadas de edad (tercera edad), etnia (cuando ésta es socialmente discriminada o marginada), discapacidad (que según la Ley 1098 de 2006, art. 36 "se entiende como una limitación física, cognitiva, mental, sensorial o cualquier otra, temporal o permanente de la persona para ejercer una o más actividades esenciales de la vida cotidiana"), ocupación u oficio. Pueden ser también desplazados, refugiados, enfermos; personas que tengan un grado inferior de poder y ante quienes pueda ejercerse alguna forma de coerción -súbditos, empleados(as), alumnos(as), feligreses(as), acompañados(as) espiritualmente-; y también aquellos(as) que pasan por una situación de duelo o confusión interior'
            ],
            [
                'id' => 'se-habla-de',
                'titulo' => 'Se habla de:',
                'contenido' => 'Maltrato físico: Agresiones voluntarias y directas contra un menor o persona vulnerable, o negligencia en cubrir sus necesidades básicas como alimentación, vestido, higiene, supervisión y atención médica.

Maltrato psicológico o emocional: Insultos, rechazo, amenazas, humillaciones, desprecio, burlas, críticas, aislamiento e intimidación de un menor o persona vulnerable. También se incluye la negligencia en sus necesidades psicológicas relacionadas con las relaciones interpersonales y la autoestima (por ejemplo, no responder a las necesidades emocionales o ignorarlas).

Cyberacoso: Publicar o enviar mensajes desagradables/amenazantes a través de redes sociales; difundir rumores o información comprometedora; exponer la intimidad de una persona para desacreditarla; etiquetar, asociar comentarios indeseables o modificar fotos para exponer a alguien a observaciones de terceros; publicar publicaciones, fotos o videos desagradables sobre la víctima en sitios web, redes sociales, chat o teléfonos móviles; grabar y difundir agresiones, insultos o actos degradantes; suplantar a la víctima o incluir contenido ofensivo en perfiles/chats; molestar e intimidar con contenido, mensajes o comentarios sexualmente explícitos; y difundir imágenes o datos sexualmente comprometedores a través de redes sociales. Estas acciones se aplican a un menor o persona vulnerable, incluso con el consentimiento de la víctima.

Maltrato sexual: El abuso sexual es cualquier acto que involucre a un niño en cualquier actividad para la gratificación sexual de otra persona, independientemente de si el niño ha acordado o consentido. El abuso sexual consiste en forzar o tentar a un niño a participar en actividades sexuales, ya sea que sea consciente o no de lo que está sucediendo.'
            ],
            [
                'id' => 'implicados',
                'titulo' => 'Implicados:',
                'contenido' => 'Hablando de maltrato y abusos a menores, por lo que se refiere a las víctimas, nos referimos siempre a una persona que no ha llegado a la edad legal de la mayoría de edad. Los 18 años marcan una línea legal donde se da por terminada la adolescencia y, con ella, la minoría de edad. Algunos niños y jóvenes son especialmente vulnerables: minusválidos; de comunidades de minorías étnicas y que sufren de discriminación; los que están refugiados o que buscan asilo; los de familias donde se abusa el alcohol o las drogas, donde hay violencia doméstica o problemas de salud mental; los huérfanos como consecuencia del VIH o del SIDA.

Se considera persona vulnerable toda persona en estado de enfermedad, deficiencia física o mental o privación de la libertad personal permanente u ocasional. Por estas causas, la persona vulnerable ve limitada su capacidad para comprender o querer resistir la ofensa. Por lo que se refiere a los agresores, nos referimos fundamentalmente a adultos, sea hombres como mujeres, pero también a adolescentes.'
            ],
            [
                'id' => 'politica-cooperacion-solidaria',
                'titulo' => '9. POLÍTICA DE COOPERACIÓN SOLIDARIA DE LA PASTORAL SOCIAL',
                'contenido' => '',
                'subsecciones' => [
                    [
                        'titulo' => 'Antecedentes',
                        'contenido' => 'Hablando de maltrato y abusos a menores, por lo que se refiere a las víctimas, nos referimos siempre a una persona que no ha llegado a la edad legal de la mayoría de edad. Los 18 años marcan una línea legal donde se da por terminada la adolescencia y, con ella, la minoría de edad. Algunos niños y jóvenes son especialmente vulnerables: minusválidos; de comunidades de minorías étnicas y que sufren de discriminación; los que están refugiados o que buscan asilo; los de familias donde se abusa el alcohol o las drogas, donde hay violencia doméstica o problemas de salud mental; los huérfanos como consecuencia del VIH o del SIDA.

Se considera persona vulnerable toda persona en estado de enfermedad, deficiencia física o mental o privación de la libertad personal permanente u ocasional. Por estas causas, la persona vulnerable ve limitada su capacidad para comprender o querer resistir la ofensa. Por lo que se refiere a los agresores, nos referimos fundamentalmente a adultos, sea hombres como mujeres, pero también a adolescentes.

Dice su prefecto,¹⁵: "El hombre redescubre hoy toda su fragilidad." Y continúa: "La Tierra, nuestra Casa Común, necesita de la solidaridad de todos para que sea más habitable y saludable. La investigación y la tecnología deben aplicarse para hacer de esta Casa más saludable y habitable. Redescubrimos la confianza de Dios en la humanidad para esta vocación de solidaridad. Redescubrimos que los destinos individuales están entrelazados. Redescubrimos el verdadero valor de las cosas y la importancia percibida de otras." Y el Papa Francisco, el 27 de marzo de 2020, nos recuerda: "La tormenta desenmascara nuestra vulnerabilidad y deja al descubierto esas falsas y superfluas seguridades con las que hemos construido nuestras agendas, nuestros proyectos, nuestros hábitos y prioridades".',
                        'nota' => '¹⁵ Palabras del cardenal Peter K.A., Turkson prefecto del Dicasterio para el Servicio del Desarrollo Humano Integral en entrevista a Vatican News. Abril 15 de 2020'
                    ],
                    [
                        'titulo' => 'Política',
                        'imagen' => 'Politica.png',
                        'contenido' => 'El Secretariado Diocesano de Pastoral Social se compromete a implementar la solidaridad con las diferentes entidades e iniciativas de la Iglesia, para fomentar la paz, la justicia, el cuidado de la creación y la solidaridad con las personas vulnerables.²⁴'
                    ],
                    [
                        'titulo' => 'Directriz para la cooperación solidaria en la incidencia:',
                        'contenido' => 'Desde la labor social de la Iglesia, la incidencia política es un proceso organizado para influir en la opinión pública y en los tomadores de decisiones, con el fin de lograr transformaciones sociales, guiados por la Doctrina Social de la Iglesia y en beneficio de los más necesitados.

La Diócesis de Apartadó comprende que la incidencia política y de opinión pública es un proceso sistemático para influir en los tomadores de decisiones en beneficio de las comunidades vulnerables, en consonancia con la Doctrina Social de la Iglesia.

La realidad nos exige tender más puentes y fomentar el diálogo social para la reconciliación. Por ello, participaremos en los debates públicos para incidir en los formuladores de políticas a nivel regional, nacional e internacional, con el objetivo de lograr una sociedad más justa y solidaria y una paz sostenible.',
                        'nota' => '"Estatuto del Dicasterio para el Servicio del Desarrollo Humano Integral. Art. 3-6"'
                    ],
                    [
                        'titulo' => 'Para ello seguiremos los siguientes principios básicos:',
                        'contenido' => '• Discrecionalidad: La vocería la tendrán personas debidamente autorizadas

• Sinceridad, respeto y conocimiento de la realidad propia y externa.

• Alianzas con actores que compartan nuestros valores y con los que podemos incidir de forma conjunta.'
                    ]
                ]
            ],
            [
                'id' => 'protocolo-anticorrupcion',
                'titulo' => '10. PROTOCOLO ANTICORRUPCIÓN Y ANTISOBORNO',
                'contenido' => '',
                'subsecciones' => [
                    [
                        'titulo' => '10.1. Finalidad',
                        'contenido' => 'El cumplimiento de la legalidad, la integridad y objetividad en la actuación son algunos de los principios y valores fundamentales que rigen la actuación de la Diócesis de Apartadó y sus dependencias, y con los que ésta se encuentra comprometida al más alto nivel. Estos principios y las pautas de conducta generales que velan por su cumplimiento están recogidos, junto con el resto de valores y principios que también inspiran la actividad de la entidad, en el Código Ético de la Diócesis de Apartadó.

El cumplimiento de estos valores y principios resulta totalmente incompatible con cualquier conducta constitutiva de corrupción. Y ello máxime teniendo en cuenta, la propia naturaleza de la actividad desarrollada por la Diócesis. De este modo se mantiene un compromiso firme y decidido en la lucha contra cualquier forma de corrupción.

El presente protocolo tiene como objetivo establecer las normas, directrices y controles necesarios para la prevención de la corrupción en Diócesis de Apartadó y sus dependencias, completando y desarrollando lo dispuesto en nuestro Código Ético, sin perjuicio de la adopción de controles adicionales derivados de normativas u obligaciones nacionales más exigentes en esta materia.'
                    ],
                    [
                        'titulo' => '10.2. Ámbito de aplicación',
                        'contenido' => 'Los destinatarios del presente protocolo son todas las personas que trabajan para institución o en su nombre, quienes están obligados al cumplimiento de todas las normas y pautas de actuación establecidas en el mismo.

Aunque de forma indirecta, también son destinatarios del presente Protocolo Anticorrupción las personas y entidades que se relacionan con la Diócesis de Apartadó como proveedores, beneficiarios, asesores, intermediarios, etc. Estos terceros deberán conocer la normativa anticorrupción vigente y aplicada por la Diócesis de Apartadó, que se plasma en el presente Protocolo.

En consecuencia, el presente protocolo es de obligado conocimiento y cumplimiento por parte de todos los empleados, contratistas y voluntarios de la Diócesis de Apartadó y sus dependencias. Este Protocolo Anticorrupción regula una serie de normas y controles aptos para la prevención de situaciones y conductas que podrían dar lugar a la comisión de delitos de corrupción pública y privada.

Lo previsto en este Protocolo Anticorrupción prevalece sobre cualquier otra norma que sea contraria a él.'
                    ],
                    [
                        'titulo' => '10.3. Alcance',
                        'contenido' => 'Entendida en sentido amplio, la corrupción incluye tanto lo que en el Código Penal español se denomina "cohecho" (el soborno de funcionarios), como el "tráfico de influencias", y tanto la corrupción en el sector público (el cohecho), como en el sector privado (la llamada "corrupción entre particulares" o "corrupción en los negocios"), así como la financiación ilegal de los partidos políticos.

En el cohecho cometen el delito tanto el que se deja corromper como el que corrompe. El delito es igual de grave en ambos casos.

El cohecho se produce tanto si la dádiva se ofrece o entrega directamente al funcionario, como si se hace a un familiar, a una persona indicada por el funcionario, o a una persona o entidad interpuesta (por ejemplo, una empresa formada por familiares, amigos o testaferros del funcionario).

Se entiende por "dádiva" cualquier cosa con valor económico: dinero, regalos, bienes, prestaciones de servicios, empleos a familiares, contratos, viajes, invitaciones a espectáculos, etc. Lo mismo se aplica a la corrupción entre particulares.'
                    ],
                    [
                        'titulo' => '10.4. Pautas de actuación en la Diócesis de Apartadó para la prevención de la corrupción',
                        'contenido' => '',
                        'subsecciones' => [
                            [
                                'titulo' => '10.4.1. Realización y ofrecimiento de retribuciones, regalos, o servicios en condiciones ventajosas',
                                'contenido' => '',
                                'subsecciones' => [
                                    [
                                        'titulo' => '10.4.1.1.',
                                        'contenido' => 'Está prohibido ofrecer o entregar a cualquier persona vinculada a la institución (o aceptar su solicitud), directamente o a través de familiares o personas interpuestas, cualquier tipo de retribución, beneficio o regalo. Esta prohibición incluye en todo caso las retribuciones, beneficios o regalos que se realicen en las siguientes condiciones:

• Condicionados, explícita o implícitamente, a que se tome una decisión en beneficio de la Diócesis y sus dependencias.

• Que constituyan directa o indirectamente una recompensa por una decisión previamente adoptada en beneficio de la Diócesis o sus dependencias en atención al cargo o función de quien lo recibe.'
                                    ],
                                    [
                                        'titulo' => '10.4.1.2.',
                                        'contenido' => 'Las retribuciones, beneficios o regalos incluyen cualquier cosa que tenga valor económico: dinero, obsequios, bienes o activos de cualquier tipo, prestaciones de servicios, empleos a familiares, contratos, viajes, invitaciones a espectáculos, etc.'
                                    ],
                                    [
                                        'titulo' => '10.4.1.3.',
                                        'contenido' => 'Está prohibido ofrecer o entregar (o aceptar su solicitud) a un directivo, administrador o servidor de cualquier empresa o entidad privada, o a cualquier tercero que contrate o tenga alguna relación de negocio con la Diócesis de Apartadó, retribuciones, beneficios o regalos para que, incumpliendo sus obligaciones en la adquisición o venta de bienes o en la contratación de servicios, favorezcan a la Diócesis de Apartadó frente a otras firmas.'
                                    ]
                                ]
                            ],
                            [
                                'titulo' => '10.4.2. Tráfico de influencias',
                                'contenido' => '',
                                'subsecciones' => [
                                    [
                                        'titulo' => '10.4.2.1.',
                                        'contenido' => 'Conforme a lo dispuesto para los delitos de corrupción en el ámbito penal, está prohibido ejercer influencia sobre un funcionario público prevaliéndose del ejercicio de las facultades de su cargo o de cualquier otra situación derivada de una previa relación personal (de parentesco, de amistad, de negocios mutuos, etc.) o jerárquica con ese concreto funcionario o con otro funcionario; y con la finalidad de obtener una decisión beneficiosa para los intereses de la Diócesis de Apartadó.'
                                    ],
                                    [
                                        'titulo' => '10.4.2.2.',
                                        'contenido' => 'Está prohibido solicitar a cualquier tercero, en nombre propio o de la Diócesis de Apartadó, una retribución, pago o recompensa de cualquier género e importe a cambio de influir indebidamente en un funcionario público en los términos descritos en el punto anterior 4.2.1.'
                                    ]
                                ]
                            ],
                            [
                                'titulo' => '10.4.3. Conocimiento del Protocolo Anticorrupción por parte de los proveedores de Pastoral Social Diócesis de Apartadó',
                                'contenido' => 'Para cumplir con los principios establecidos en este protocolo, la adjudicación de contratos por parte de la Diócesis de Apartadó a sus proveedores de productos y servicios deberá basarse en los principios de publicidad, concurrencia, transparencia, confidencialidad, igualdad y no discriminación de acuerdo con lo dispuesto en la legalidad vigente y en la normativa interna de la Diócesis de Apartadó en materia de contratación.'
                            ]
                        ]
                    ],
                    [
                        'titulo' => '10.5. Canal de denuncias',
                        'contenido' => 'Los administradores, directores y empleados que tengan conocimiento de cualquier acto que pueda constituir un incumplimiento del presente protocolo, están obligados a denunciarlo lo antes posible, utilizando el Canal de Denuncias interno establecido en la Diócesis de Apartadó.'
                    ],
                    [
                        'titulo' => '10.6. Aprobación, entrada en vigor y revisión de este protocolo',
                        'contenido' => 'El presente Protocolo Anticorrupción entrará en vigor el día de su publicación y permanecerá vigente hasta que se apruebe su anulación, teniendo efectos vinculantes para todos sus destinatarios. El protocolo estará sujeto a revisión y actualización continua por parte de la dirección, especialmente cuando sea posible introducir mejoras o cuando se identifique un riesgo de corrupción previamente no apreciado.'
                    ]
                ]
            ],
            [
                'id' => 'protocolo-equidad-genero',
                'titulo' => '11. PROTOCOLO DE EQUIDAD DE GÉNERO',
                'contenido' => '',
                'subsecciones' => [
                    [
                        'titulo' => 'Objetivo general',
                        'contenido' => 'Promover y visibilizar la dignidad diferenciada de hombres y mujeres y su importancia en los procesos acompañados por la Diócesis de Apartadó y sus dependencias.'
                    ],
                    [
                        'titulo' => 'Contexto general y antecedentes',
                        'contenido' => 'Durante décadas Colombia ha realizado un gran despliegue en formulación de políticas públicas, normatividad y aprobación de pactos internacionales, para lograr equilibrar las oportunidades y fortalecer la equidad de género en el país. Sin embargo, no fue sino hasta después de la promulgación de la Constitución de 1991, con sus desarrollos sobre el principio de igualdad y no discriminación, sumado a los impactos de las conferencias mundiales sobre la mujer realizadas por Naciones Unidas, especialmente la de Beijing 1995, que se dio un impulso continuo a la elaboración de políticas incorporando la igualdad de oportunidades, la categoría de análisis de género y la búsqueda del empoderamiento de las mujeres.'
                    ],
                    [
                        'titulo' => 'Principios que fundamentan la propuesta',
                        'contenido' => '',
                        'subsecciones' => [
                            [
                                'titulo' => 'I. Enfoque diferencial',
                                'contenido' => 'Este protocolo busca garantizar un procedimiento especial y coherente con las normativas vigentes y sus conceptos o lineamientos políticos y eclesiales/ teológicos en relación a las diferencias de las poblaciones involucradas.'
                            ],
                            [
                                'titulo' => 'II. Igualdad de género',
                                'contenido' => 'Igualdad de derechos, responsabilidades y oportunidades de las mujeres y los hombres, y las niñas y los niños. La igualdad no significa que las mujeres y los hombres sean lo mismo, sino que los derechos, las responsabilidades y las oportunidades no dependen del sexo con el que nacieron. La igualdad de género supone que se tengan en cuenta los intereses, las necesidades y las prioridades tanto de las mujeres como de los hombres, y que se reconozca la diversidad de los diferentes grupos de mujeres y de hombres.'
                            ],
                            [
                                'titulo' => 'III. Equidad de género',
                                'contenido' => 'Imparcialidad en el trato que reciben mujeres y hombres de acuerdo con sus necesidades respectivas, ya sea con un trato igualitario o con uno diferenciado pero que se considera equivalente en lo que se refiere a los derechos, los beneficios, las obligaciones y las posibilidades. En el ámbito del desarrollo, un objetivo de equidad de género a menudo requiere incorporar medidas encaminadas a compensar las desventajas históricas y sociales que arrastran las mujeres.'
                            ],
                            [
                                'titulo' => 'IV. Libre desarrollo de la personalidad',
                                'contenido' => 'El artículo 16 de la constitución colombiana dice que todas las personas tienen derecho al libre desarrollo de su personalidad sin más limitaciones que la que imponen los derechos de los demás y el orden jurídico.'
                            ],
                            [
                                'titulo' => 'V. Integridad',
                                'contenido' => 'En conformidad con los valores fundadores y del carácter de la institución y la coherencia en la aplicación del protocolo con transparencia y de'
                            ],
                            [
                                'titulo' => 'VI. Acciones afirmativas',
                                'contenido' => 'Iniciativas y acciones implementadas desde los proyectos, propuestas y la cotidianidad de los miembros que conforman la institución.'
                            ]
                        ]
                    ],
                    [
                        'titulo' => 'Ámbito de aplicación',
                        'contenido' => 'Este protocolo involucra a toda la comunidad diocesana independiente del rol (profesionales al servicio de la institución, aliados, colaboradores, contratistas, grupos participantes acompañados desde las pastorales, practicantes, becarios etc.), además de personal que acceda a las distintas instalaciones de la institución. Estas acciones buscan contribuir en los esfuerzos desde varios sectores de la sociedad para alcanzar la equidad de género y la no discriminación.

La Diócesis de Apartadó sigue sumando esfuerzos en acciones afirmativas que pretenden garantizar igualdad de oportunidades, cero tolerancias con la discriminación en cualquiera de sus formas y seguir posibilitando la inclusión y la equidad en sus procesos institucionales.'
                    ],
                    [
                        'titulo' => 'Conductas que atentan contra la equidad de género',
                        'contenido' => 'a. La utilización de palabras ofensivas, discriminación, exclusión relacionada con su género o diversidad sexual.

b. Impedir el acceso a las instalaciones con argumentos relacionados con su género o diversidad sexual.

c. La descalificación la capacidad intelectual y académica por su género o diversidad sexual.

d. Ejercer presión o coaccionar a una persona para obtener favores o beneficios personales a causa del género o la diversidad sexual.

e. Establecer mecanismos de promoción o incentivos laborales atendiendo al género o sexualidad diversa.

f. Realización de comentarios ofensivos debido a su diversidad sexual o su género en presencia o no de los afectados.

a. Ejercer cualquier tipo de violencia, a cualquier persona a causa del género o de la diversidad sexual.

b. La realización de comentarios y actos ofensivos por causas del género, delante de terceros.

c. Negar un servicio o la participación en los procesos institucionales a cualquier persona a causa de su género.

d. Coartar la libre expresión de cualquier persona en razón de su género o diversidad sexual.

e. Adjudicar más responsabilidades laborales y menos oportunidades de ascenso en razón del género.

f. Faltar a los principios que fundamentan esta propuesta como parte orientadora de la misma.'
                    ],
                    [
                        'titulo' => 'Acciones',
                        'contenido' => 'Se proponen una serie de acciones preventivas, correctivas o sancionatorias que permitan hacer de este protocolo un instrumento operativo que contribuye en todas sus formas en la disminución de la discriminación por el género o la diversidad sexual de las personas en el ámbito comunitario e institucional.',
                        'subsecciones' => [
                            [
                                'titulo' => 'Difusión y prevención',
                                'contenido' => 'La institución implementara acciones para visibilizar los procesos afirmativos y campañas preventivas en temas de género y establecerá un seguimiento periódico que dé cuenta del avance en el proceso de sensibilización en las políticas de género y en la identificación de conductas y posibles agresiones relacionadas con discriminación, marginación, ofensas asociadas con violencia de género y diversidad sexual.'
                            ],
                            [
                                'titulo' => 'Formación y acompañamiento',
                                'contenido' => 'La Diócesis de Apartadó complementará y promoverá estrategias de sensibilización, reconocimiento y valoración del otro y la transformación de imaginarios en la tarea de mitigar y prevenir los casos de discriminación y excusión relacionados con la violencia de género y la diversidad sexual.'
                            ],
                            [
                                'titulo' => 'Ruta de atención',
                                'contenido' => 'La Diócesis Apartadó se acoge a los lineamientos para la atención a personas víctimas de violencia de género y violencia sexual la ley 1247 de 2008, especialmente en su artículo 15.'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'id' => 'manual-relacionamiento-institucional',
                'titulo' => '12. MANUAL DE RELACIONAMIENTO INSTITUCIONAL',
                'contenido' => 'La Diócesis de Apartadó complementará y promoverá estrategias de sensibilización, reconocimiento y valoración del otro y la transformación de imaginarios en la tarea de mitigar y prevenir los casos de discriminación y excusión relacionados con la violencia de género y la diversidad sexual.',
                'subsecciones' => [
                    [
                        'titulo' => 'Objetivo',
                        'contenido' => 'Este documento busca establecer las directrices que requieren las áreas y las distintas estrategias o programas que adelantan la Diócesis de Apartadó y sus dependencias, para un adecuado relacionamiento con los grupos de interés y el logro de los objetivos de la entidad, de forma armoniosa con el desarrollo sostenible.'
                    ],
                    [
                        'titulo' => 'Responsabilidad',
                        'contenido' => 'La responsabilidad de la Diócesis de Apartadó y sus dependencias ante los impactos que sus decisiones y actividades ocasionan en la sociedad y el medio ambiente, opta por un comportamiento ético y transparente que contribuya, desde la "acción sin daño", al desarrollo sostenible, incluyendo la salud y el bienestar de la sociedad; tome en consideración las expectativas de la población diocesana; cumpla con la legislación aplicable y sea coherente con la normativa internacional de comportamiento.'
                    ],
                    [
                        'titulo' => 'Principios',
                        'contenido' => 'La Diócesis de Apartadó y sus dependencias reconocen los siguientes principios orientadores en su relación con los aliados, organizaciones e instituciones, con los que deberá observar fielmente los siguientes principios:',
                        'subsecciones' => [
                            [
                                'titulo' => 'Transparencia',
                                'contenido' => 'La información sobre las políticas, decisiones y actividades sobre las que es responsable, incluyendo los impactos conocidos y probables en lo ambiental, económico y social, será revelada de forma clara, precisa y completa.'
                            ],
                            [
                                'titulo' => 'Legalidad',
                                'contenido' => 'Cada una de las actuaciones de la Diócesis de Apartadó y sus dependencias, se sujetaran de conformidad a las leyes vigentes y su jurisdicción y no a la voluntad de las personas.'
                            ],
                            [
                                'titulo' => 'Igualdad',
                                'contenido' => 'Para la Diócesis de Apartadó y sus dependencias, todas las personas nacen libres e iguales ante la ley, recibirán la misma protección y trato dentro de la institución y gozarán de los mismos derechos, libertades y oportunidades sin ninguna discriminación por razones de sexo, raza, origen nacional o familiar, lengua, religión, opinión política o filosófica.'
                            ],
                            [
                                'titulo' => 'Equidad',
                                'contenido' => 'La Diócesis de Apartadó actuará de forma imparcial para reconocer el derecho de cada persona.'
                            ],
                            [
                                'titulo' => 'Comportamiento ético',
                                'contenido' => 'Se observarán los principios y demás lineamientos éticos establecidos por los códigos de conducta.'
                            ],
                            [
                                'titulo' => 'Respeto a los intereses de los aliados',
                                'contenido' => 'Se reconoce y demuestra el debido respeto por sus expectativas y por sus derechos legales y se responde a las inquietudes por ellos planteadas.'
                            ],
                            [
                                'titulo' => 'Respeto al principio de la legalidad y a la normativa internacional de comportamiento',
                                'contenido' => 'Se cumple con todas las leyes y regulaciones aplicables y se respeta la normativa internacional de comportamiento.'
                            ],
                            [
                                'titulo' => 'Respeto a los Derechos Humanos',
                                'contenido' => 'Se respetan los derechos humanos y se reconoce tanto su importancia como su universalidad. La Diócesis de Apartadó y sus dependencias actuarán bajo un marco de debida diligencia buscando respetar, proteger y, en los casos en que se falle, actuar para remediar las situaciones detectadas y/o denunciadas.'
                            ]
                        ]
                    ],
                    [
                        'titulo' => 'Conclusión',
                        'contenido' => 'Las relaciones con la institucionalidad o personal de interés efectiva y estratégicamente alineadas sirven para:

• Facilitar una mejor gestión de riesgo y reputación.
• Desarrollar la confianza entre las instituciones o personal de interés.
• Posibilitar la comprensión del contexto complejo de las relaciones, incluso el desarrollo de objetivos y la identificación de nuevas oportunidades estratégicas.
• Informar, educar e influenciar a las instituciones o personal de interés y al entorno para mejorar sus procesos de toma de decisiones y las acciones que afectan a las compañías y a la sociedad.

• Conducir a un desarrollo social más equitativo y sostenible al brindar una oportunidad de participar en los procesos de toma de decisiones a quienes tienen derecho a ser escuchados.
• Permitir la combinación de recursos (conocimiento, personas, dinero y tecnología) que resuelva los problemas y alcance objetivos que las organizaciones no pueden lograr de forma independiente.'
                    ]
                ]
            ],
            [
                'id' => 'normas-fuerzas-armadas',
                'titulo' => '13. NORMAS BÁSICAS POR LAS QUE SE HAN DE REGIR Y LAS RELACIONES CON LAS FUERZAS ARMADAS, LEGALES E ILEGALES',
                'contenido' => 'La Diócesis de Apartadó y sus dependencias, asumen las normas básicas por las que se han de regir las relaciones con las fuerzas militares y de policía, además de otros actores armados que se mueven por el territorio así estén al margen de la ley.

Las normas que se enuncian a continuación serán cumplidas por el personal que labora con la diócesis o sus dependencias, los voluntarios y demás personal externo que apoye actividades en el territorio diocesano. Estas normas están contenidas en tres apartados: Identidad de la Diócesis, principios básicos y principios de funcionamiento.',
                'subsecciones' => [
                    [
                        'titulo' => 'Identidad de la Diócesis',
                        'contenido' => 'La Diócesis como parte de la sociedad humana tiene una incidencia natural en las realidades propias que conforman la sociedad de la cual hace parte.

Su misión evangelizadora le lleva a viajar por todo el territorio donde haya comunidades que esperan su presencia y su anuncio profético frente a las injusticias, la violación de derechos y toda situación que pone en riesgo la vida humana.

Su tarea evangelizadora contempla el acompañamiento a las personas y grupos humanos víctimas de cualquier tipo de violencia, la escucha y el diálogo con todas las instituciones y los actores que tienen presencia e influencia en el territorio.

La Diócesis de Apartadó y cualquiera de sus dependencias tienen como prioridad en el ejercicio de su servicio humanitario la protección de la vida e integridad de las personas, la prevención de los conflictos, la promoción de la cultura de paz, el perdón, la reconciliación y la reparación.'
                    ],
                    [
                        'titulo' => 'Principios de funcionamiento',
                        'contenido' => 'En situaciones de conflictos sociales y políticos, para dar respuesta a las necesidades humanitarias, la diócesis y sus dependencias se apoyarán en las parroquias y articularan con las instituciones locales, las organizaciones territoriales y otros agentes humanitarios siguiendo estos principios:

• Conservar siempre la neutralidad y la independencia.
• Mantener una línea clara de comunicación, evitando compartir contenidos partidistas que pueda afectar a las personas o comunidades.
• No actuará sometiéndose al control de ningún grupo armado, pero acatará las disposiciones legales de las fuerzas armadas legalmente constituidas.
• Trabajará independientemente sin que ello represente un alto riesgo para los integrantes de los equipos de trabajo ni para la población atendida.
• Hará un análisis previo de contexto y coyuntura para asumir un rol responsable en las relaciones que imponga la situación con respecto a cualquier actor armado.
• Analizará con anticipación el impacto de las relaciones con los actores armados y sus posibles riesgos.

• Las parroquias son los aliados para toda la labor de acción social y es necesario atender sus recomendaciones, especialmente cuando las intervenciones se realicen en lugares y/o ambientes complejos.
• No tomarán decisiones que sean de responsabilidad de las instancias de dirección, a los que trasladaran los encargados las problemáticas que puedan surgir.
• Se hará un uso adecuado de la información y la confidencialidad de las comunicaciones que comparten personas de las comunidades.
• Se informará adecuada y públicamente sobre el objeto y alcances de cada intervención.'
                    ]
                ]
            ],
            [
                'id' => 'politica-seguridad-salud-trabajo',
                'titulo' => '13. POLÍTICA DE SEGURIDAD Y SALUD EN EL TRABAJO',
                'contenido' => 'La Diócesis de Apartadó y sus dependencias, en conjunto con sus propósitos misionales y de evangelización, consideran que es de gran importancia velar por la seguridad y la salud de sus empleados, contratistas, proveedores, organizaciones civiles, grupo étnicos, familias y comunidad en general, por ello su compromiso es implementar un sistema de seguridad y salud en el trabajo encaminado a velar por el completo bienestar físico, mental y social de cada uno de sus colaboradores y miembros de la organización, ofreciendo lugares de trabajo seguros y adecuados para el desarrollo de cada actividad. Dando de igual forma cumplimiento a los requisitos de la legislación nacional e internacional vigente en materia de prevención de riesgos, accidentes de trabajo y enfermedades laborales.

Todo lo anterior deberá cumplirse bajo los siguientes parámetros:

• Procurar, mantener un ambiente de trabajo sano y seguro, desde el ámbito físico y/o psicoemocional, controlando los riesgos potenciales y reales, previniendo las lesiones, accidentes y enfermedades laborales.
• Controlar y minimizar los riesgos, estará en el primer lugar de las prioridades en el desarrollo de cualquier actividad.
• Suministrar los recursos humanos, técnicos y financieros que garanticen el cumplimiento de los objetivos y actividades propuestas.

• Todo el personal contratado estará cubierto con seguridad social y riesgos profesionales y se exigirá a los contratistas que también cumplan esta norma.
• Promover y respaldar la participación del Comité Paritario de Seguridad y Salud en el Trabajo COPASST, en el S -G S S T.
• Todos los colaboradores y contratistas son responsables en primera instancia del cumplimiento eficiente y eficaz de su salud, del cuidado de su seguridad, la del personal bajo su cargo, la de la empresa y el de participar proactivamente en las actividades de y promoción de la seguridad y salud. Igualmente serán responsables de notificar oportunamente todas aquellas condiciones que puedan generar consecuencias y contingencias para los colaboradores y la organización.
• Todo el personal conoce esta política y la ruta de atención necesaria si nota deficiencias en su implementación.
• Examinar y revisar esta política periódicamente.'
            ]
        ];

        return view('recursos.protocolos', compact('secciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo administradores pueden subir recursos
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden subir recursos');
        }
        
        return view('recursos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Solo administradores pueden subir recursos
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden subir recursos');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:10240', // Máximo 10MB
        ]);

        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $rutaArchivo = $archivo->storeAs('recursos', $nombreArchivo, 'public');
        
        // Determinar tipo de archivo
        $extension = strtolower($archivo->getClientOriginalExtension());
        $tipoArchivo = in_array($extension, ['doc', 'docx']) ? 'word' : 'pdf';

        $recurso = Recurso::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'archivo' => $rutaArchivo,
            'tipo_archivo' => $tipoArchivo,
            'tamaño' => $archivo->getSize(),
            'mime_type' => $archivo->getMimeType(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('recursos.index')
            ->with('success', 'Recurso subido exitosamente.');
    }

    /**
     * Download the specified resource.
     */
    public function download(Recurso $recurso)
    {
        // Verificar que el archivo existe
        if (!Storage::disk('public')->exists($recurso->archivo)) {
            abort(404, 'El archivo no existe');
        }

        $extension = $recurso->tipo_archivo === 'word' ? 'docx' : 'pdf';
        $nombreArchivo = $recurso->nombre . '.' . $extension;
        
        return Storage::disk('public')->download($recurso->archivo, $nombreArchivo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recurso $recurso)
    {
        // Solo administradores pueden eliminar recursos
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden eliminar recursos');
        }

        // Eliminar el archivo del almacenamiento
        if (Storage::disk('public')->exists($recurso->archivo)) {
            Storage::disk('public')->delete($recurso->archivo);
        }

        // Eliminar el registro de la base de datos
        $recurso->delete();

        return redirect()->route('recursos.index')
            ->with('success', 'Recurso eliminado exitosamente.');
    }
}
