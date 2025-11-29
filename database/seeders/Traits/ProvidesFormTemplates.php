<?php 
namespace Database\Seeders\Traits;

trait ProvidesFormTemplates
{
    /**
     * Devuelve el array de datos de las plantillas de formulario.
     */
    protected function getFormTemplatesData(): array
    {
        return [
            // Formulario 1: Grúa Horquilla
            [
                'name' => 'Hoja de Registro Conductual – Grúa Horquilla (HRC-GH)',
                'description' => 'Observación conductual en faenas de carga/descarga y traslado con grúa horquilla. Contempla conducción, interacción peatón–equipo, orden y limpieza, causas, compromisos, post operación y cierre.',
                'sections' => [
                    [
                        'name' => 'Conducción y Maniobras',
                        'questions' => [
                            'Conduce a velocidad segura acorde a condiciones del patio',
                            'Mantiene horquillas a altura segura durante el desplazamiento',
                            'Mantiene distancia segura respecto de peatones y otros equipos',
                            'Realiza giros y retrocesos con precaución y señalización (radio/señales)',
                            'Usa cinturón y permanece dentro de la cabina; sin uso de celular',
                        ],
                    ],
                    [
                        'name' => 'Interacción con Peatones y Entorno',
                        'questions' => [
                            'Coordina maniobras con señalero/operador asignado cuando aplica',
                            'Utiliza barreras duras/segregación al trabajar cerca de peatones',
                            'No circula con carga elevada sobre personas ni por pasos peatonales',
                            'Estiba/desestiba sin golpear racks, estructuras o vehículos',
                            'Mantiene el área con orden y limpieza; vías de tránsito demarcadas y despejadas',
                        ],
                    ],
                    [
                        'name' => 'Post Operación y Cierre',
                        'questions' => [
                            'Estaciona en zona autorizada, con superficie nivelada y señalización correspondiente',
                            'Baja horquillas al suelo, neutro aplicado y freno de estacionamiento activado',
                            'Apaga el equipo, retira llave/tarjeta y aplica bloqueo si corresponde',
                            'Deja área de trabajo ordenada y limpia; residuos gestionados según procedimiento',
                            'Reporta fallas/condiciones inseguras y completa registro/cierre de la tarea',
                        ],
                    ],
                ],
            ],

            // Formulario 2: Trabajo en Altura (PEMP)
            [
                'name' => 'Hoja de Registro Conductual – Trabajo en Altura (PEMP)',
                'description' => 'Instrumento operativo para observar y registrar conductas durante trabajos en altura con plataforma elevadora (PEMP/manlift)',
                'sections' => [
                    [
                        'name' => 'Pre-uso y Preparación del Equipo',
                        'questions' => [
                            'Checklist pre-uso de la PEMP completado (controles, mandos, alarmas, emergencia)',
                            'Inspección de arnés, eslinga y punto de anclaje correcta y vigente',
                            'Superficie de apoyo nivelada, firme y sin obstrucciones (calzos si aplica)',
                            'Verificación de riesgos del entorno: tendidos eléctricos, viento, objetos suspendidos',
                        ],
                    ],
                    [
                        'name' => 'Operación y Estabilidad en Altura',
                        'questions' => [
                            'Uso permanente de arnés anclado a punto certificado del canastillo',
                            'Carga dentro del límite de la PEMP; distribución y herramientas aseguradas',
                            'Movimientos suaves y controlados (sin sacudidas ni traslación con brazo extendido indebida)',
                            'Mantiene distancia y no trabaja bajo carga/elementos suspendidos',
                            'Comunicación efectiva con señalero/spotter y control de área bajo la plataforma',
                        ],
                    ],
                    [
                        'name' => 'Post-operación y Retiro Seguro',
                        'questions' => [
                            'Descenso controlado y estacionamiento en zona autorizada, brazo retraído y base nivelada',
                            'Equipo apagado, llaves retiradas y bloqueo/etiquetado si corresponde',
                            'Revisión post-uso y reporte de fallas/daños; registro actualizado',
                            'Retiro de señalización temporal y limpieza del área intervenida',
                            'Verificación de cierre de permisos de trabajo y notificación a encargado del área',
                        ],
                    ],
                ],
            ],

            // Formulario 3: Espacios Confinados (IEC)
            [
                'name' => 'Hoja de Registro Conductual – Ingreso a Espacios Confinados (IEC) — Equipo: Excavadora',
                'description' => 'Instrumento operativo para planificar, observar y registrar condiciones y conductas durante el ingreso y trabajo en espacios confinados, considerando la interacción y control de equipos auxiliares (en este caso, Excavadora) que puedan influir en la seguridad del sitio.',
                'sections' => [
                    [
                        'name' => 'Aislamiento y Preparación del Sitio',
                        'questions' => [
                            'Fuentes de energía identificadas y bloqueadas/etiquetadas (LOTO) según procedimiento',
                            'Excavadora detenida, con freno aplicado; motor apagado y controlada si hay riesgo de interacción',
                            'Ventilación forzada instalada y operativa; renovación de aire suficiente',
                            'Señalización y segregación del área (barreras, cintas, letreros) implementadas',
                            'Sistema de comunicación probado (radio/señales); iluminación adecuada y segura',
                        ],
                    ],
                    [
                        'name' => 'Cierre y Restitución del Área',
                        'questions' => [
                            'Egreso controlado de personal/herramientas; verificación de aforo “cero personas”',
                            'Gasometría final registrada; sin atmósferas peligrosas remanentes',
                            'Retiro de ventilación, señalización y barreras; área limpia y ordenada',
                            'Cierre del permiso de trabajo IEC con firmas; documentación completada',
                            'Restitución segura de energías (retiro LOTO conforme) y comunicación a operación',
                        ],
                    ],
                ],
            ],
        ];
    }
}
?>