<?php


// RMV-NOTIFY

// Text for various templates...

define ('_NOT_NOTIFICATIONOPTIONS', 'Opções de aviso');
define ('_NOT_UPDATENOW', 'Atualizar');
define ('_NOT_UPDATEOPTIONS', 'Opções de avisos sobre atualizações');

define ('_NOT_CLEAR', 'Limpar');
define ('_NOT_CHECKALL', 'Verificar tudo');
define ('_NOT_MODULE', 'Módulo');
define ('_NOT_CATEGORY', 'Categoria');
define ('_NOT_ITEMID', 'Id');
define ('_NOT_ITEMNAME', 'Nome');
define ('_NOT_EVENT', 'Evento');
define ('_NOT_EVENTS', 'Eventos');
define ('_NOT_ACTIVENOTIFICATIONS', 'Avisos ativos');
define ('_NOT_NAMENOTAVAILABLE', 'Nome não disponível');
// RMV-NEW : TODO: remove NAMENOTAVAILBLE above
define ('_NOT_ITEMNAMENOTAVAILABLE', 'Nome do item não disponível');
define ('_NOT_ITEMTYPENOTAVAILABLE', 'Tipo de item não disponível');
define ('_NOT_ITEMURLNOTAVAILABLE', 'URL do item não disponível');
define ('_NOT_DELETINGNOTIFICATIONS', 'Excluindo avisos');
define ('_NOT_DELETESUCCESS', 'Avisos excluí­dos corretamente');
define ('_NOT_UPDATEOK', 'Opções de aviso atualizadas');
define ('_NOT_NOTIFICATIONMETHODIS', 'Modo de aviso');
define ('_NOT_EMAIL', 'e-mail');
define ('_NOT_PM', 'Mensagem particular');
define ('_NOT_DISABLE', 'Desativado');
define ('_NOT_CHANGE', 'Alterar');

define ('_NOT_NOACCESS', 'Você não tem permissão para acessar essa página');

// Text for module config options

define ('_NOT_NOTIFICATION', 'aviso');

define ('_NOT_CONFIG_ENABLED', 'Ativar avisos');
define ('_NOT_CONFIG_ENABLEDDSC', 'Este módulo permite que os usuários sejam avisado quando certos eventos ocorrerem. Marque "sim" para ativar esta opção.');

define ('_NOT_CONFIG_EVENTS', 'Ativar eventos específicos');
define ('_NOT_CONFIG_EVENTSDSC', 'Selecione os eventos que os usuários podem usar.');

define ('_NOT_CONFIG_ENABLE', 'Ativar Notificação');
define ('_NOT_CONFIG_ENABLEDSC', 'Este módulo permite que os usuários sejam avisados quando certos eventos ocorrerem.<br/><u>Estilo Bloco</u> - Mostra opções de aviso em um bloco, que deverá estar ativo na página do módulo.<br/><u>Estilo Inserido</u> - Inclui uma tabela no rodapé da página principal do módulo.');
define ('_NOT_CONFIG_DISABLE', 'Desativar avisos');
define ('_NOT_CONFIG_ENABLEBLOCK', 'Ativar estilo bloco');
define ('_NOT_CONFIG_ENABLEINLINE', 'Ativar estilo inserido');
define ('_NOT_CONFIG_ENABLEBOTH', 'Ativar ambos os estilos');

// For notification about comment events

define ('_NOT_COMMENT_NOTIFY', 'Novo comentário');
define ('_NOT_COMMENT_NOTIFYCAP', 'Avise-me quando um novo comentário for publicado neste item.');
define ('_NOT_COMMENT_NOTIFYDSC', 'Receba aviso quando um novo comentário for publicado neste item.');
define ('_NOT_COMMENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Aviso: Comentário incluí­do para {X_ITEM_TYPE}');

define ('_NOT_COMMENTSUBMIT_NOTIFY', 'Novo comentário para análise');
define ('_NOT_COMMENTSUBMIT_NOTIFYCAP', 'Avise-me quando um novo comentário for enviado para análise');
define ('_NOT_COMMENTSUBMIT_NOTIFYDSC', 'Receba aviso quando um novo comentário, neste item, for enviado para análise');
define ('_NOT_COMMENTSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Aviso: Comentário enviado para {X_ITEM_TYPE}');

// For notification bookmark feature
// (Not really notification, but easy to do with this module)

define ('_NOT_BOOKMARK_NOTIFY', 'Incluir como favorito');
define ('_NOT_BOOKMARK_NOTIFYCAP', 'Inclui o item como favorito, sem aviso.');
define ('_NOT_BOOKMARK_NOTIFYDSC', 'Acompanhe este item sem receber avisos de qualquer evento.');

// For user profile
// FIXME: These should be reworded a little...

define ('_NOT_NOTIFYMETHOD', 'Modo de aviso<br />Ao monitorar um módulo, como você gostaria de ser avisado?');
define ('_NOT_METHOD_EMAIL', 'E-mail registrado no perfil');
define ('_NOT_METHOD_PM', 'Mensagem particular');
define ('_NOT_METHOD_DISABLE', 'Desativar');

define ('_NOT_NOTIFYMODE', 'Modo de aviso padrão');
define ('_NOT_MODE_SENDALWAYS', 'Avise-me sobre todas as atualizações selecionadas');
define ('_NOT_MODE_SENDONCE', 'Avise-me apenas uma vez');
define ('_NOT_MODE_SENDONCEPERLOGIN', 'Avise-me uma vez e suspenda até minha próxima visita.');

define ('_NOT_NOTHINGTODELETE', 'Não há nada para apagar.');

// Added in 1.3.1
define("_NOT_RUSUREDEL", "Are you sure you want to delete these notifications?");