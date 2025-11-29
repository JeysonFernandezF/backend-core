<?php

// Lista de todos los permisos de la aplicación.
return [
            // Permisos
            'permissions.index',
            'permissions.store',
            'permissions.show',
            'permissions.update',
            'permissions.destroy',

            // Roles
            'roles.index',
            'roles.store',
            'roles.show',
            'roles.update',
            'roles.destroy',

            // Catalog: Activities
            'activities.index',
            'activities.store',
            'activities.show',
            'activities.update',
            'activities.destroy',

            // Catalog: Equipments
            'equipments.index',
            'equipments.store',
            'equipments.show',
            'equipments.update',
            'equipments.destroy',

            // Catalog: Worksites
            'worksites.index',
            'worksites.store',
            'worksites.show',
            'worksites.update',
            'worksites.destroy',

            // Catalog: Positions
            'positions.index',
            'positions.store',
            'positions.show',
            'positions.update',
            'positions.destroy',

            // Catalog: Barriers
            'barriers.index',
            'barriers.store',
            'barriers.show',
            'barriers.update',
            'barriers.destroy',

            // Catalog: Companies
            'companies.index',
            'companies.store',
            'companies.show',
            'companies.update',
            'companies.destroy',

            // Catalog: Managements
            'managements.index',
            'managements.store',
            'managements.show',
            'managements.update',
            'managements.destroy',

            // Catalog: Departments
            'departments.index',
            'departments.store',
            'departments.show',
            'departments.update',
            'departments.destroy',

            // Catalog: Areas
            'areas.index',
            'areas.store',
            'areas.show',
            'areas.update',
            'areas.destroy',

            // Catalog: Critical Risks
            'critical-risks.index',
            'critical-risks.store',
            'critical-risks.show',
            'critical-risks.update',
            'critical-risks.destroy',

            // Catalog: Turns
            'turns.index',
            'turns.store',
            'turns.show',
            'turns.update',
            'turns.destroy',

            // Catalog: Conducts
            'conducts.index',
            'conducts.store',
            'conducts.show',
            'conducts.update',
            'conducts.destroy',

            // Catalog: Observed Tasks
            'observed-tasks.index',
            'observed-tasks.store',
            'observed-tasks.show',
            'observed-tasks.update',
            'observed-tasks.destroy',

            // Program Details
            'program-details.index',
            'program-details.store',
            'program-details.show',
            'program-details.update',
            'program-details.destroy',

            // Program Registers
            'program-registers.index',
            'program-registers.store',
            'program-registers.show',
            'program-registers.update',
            'program-registers.destroy',
            'program-registers.getCatalog', // Endpoint personalizado
            'program-registers.storeProgramRegisterWithDetails', // Endpoint personalizado
            'program-register.getProgramsByWorksites', // Endpoint personalizado 
            // Forms
            'forms.index',
            'forms.store',
            'forms.show',
            'forms.update',

            // Observations
            'observations.index',
            'observations.store',
            'observations.show',
            'observations.update',
            'observations.destroy',
            'observations.getCatalog', // Endpoint personalizado
            'observations.showObservationWithQuestions', // Endpoint personalizado
            'observations.storeRecordQuestions', // Endpoint personalizado
            'observations.showFormWithAnswer', // Endpoint personalizado

            // Template Forms
            'template-forms.index',
            'template-forms.store',
            'template-forms.show',
            'template-forms.update',
            'template-forms.destroy',
            'template-forms.indexWithoutPaginate', // Endpoint personalizado
        
            // Usuarios
            'users.index', // Endpoint personalizado
            'users.assign-roles' // Endpoint personalizado
        ];

?>