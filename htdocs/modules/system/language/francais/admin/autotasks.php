<?php
define('_CO_ICMS_AUTOTASKS_NAME', 'Nom de tâche');
define('_CO_ICMS_AUTOTASKS_NAME_DSC', 'Entrer le nom de la tâche');
define('_CO_ICMS_AUTOTASKS_CODE', 'Code source');
define('_CO_ICMS_AUTOTASKS_CODE_DSC', 'Ecrivez le code PHP qui doit etre execute comme une tache. <br /><br />mainfile.php sera deja insere.<br />Utiliser <i>global $xoopsDB</i> pour utiliser l\' objet DB.');
define('_CO_ICMS_AUTOTASKS_REPEAT', 'Répéter');
define('_CO_ICMS_AUTOTASKS_REPEAT_DSC', 'Quelle sera la frequence? Rajouter \'0\' pour definir une tache perpetuelle');
define('_CO_ICMS_AUTOTASKS_INTERVAL', 'Intervalle');
define('_CO_ICMS_AUTOTASKS_INTERVAL_DSC', 'Intervalle d\'execution des tâches (en minutes).<br /><br />60: une fois par heure<br />1440: une fois par jour');
define('_CO_ICMS_AUTOTASKS_ONFINISH', 'Effacement automatique');
define('_CO_ICMS_AUTOTASKS_ONFINISH_DSC', 'Voulez-vous que cette tache est automatiquement efface apres un nombre de repetitions? Selectionner \'Oui\' quand vous voulez que la tache s\' efface automatiquement ou \'Non\' afin de pauser la tache.<br />Ceci est prise en compte uniquement avec des frequences superieur a \'0\'.');
define('_CO_ICMS_AUTOTASKS_ENABLED', 'Autorisé');
define('_CO_ICMS_AUTOTASKS_ENABLED_DSC', 'Choisir \'Oui\' pour autoriser la tâche');
define('_CO_ICMS_AUTOTASKS_TYPE', 'Type');
define('_CO_ICMS_AUTOTASKS_LASTRUNTIME', 'Temp du dernier exécution');
define('_CO_ICMS_AUTOTASKS_CREATE', 'Créer une nouvelle tâche');
define('_CO_ICMS_AUTOTASKS_EDIT', 'Modifier tâche');
define('_CO_ICMS_AUTOTASKS_CREATED', 'Tâche rajoutée');
define('_CO_ICMS_AUTOTASKS_MODIFIED', 'Tâche modifiée');
define('_CO_ICMS_AUTOTASKS_NOTYETRUNNED', 'Pas encore executé');
define('_CO_ICMS_AUTOTASKS_TYPE_CUSTOM', 'Utilisateur');
define('_CO_ICMS_AUTOTASKS_TYPE_ADDON', 'Système');
define('_CO_ICMS_AUTOTASKS_FOREVER', 'toujours');
define('_CO_ICMS_AUTOTASKS_INIT_ERROR', 'Erreur: Impossible de initialiser le sous-système des tâches spécifié');
define('_CO_ICMS_AUTOTASKS_SOURCECODE_ERROR', 'Erreur dans le code source de Autotask : impossible de executer Autotask');
?>