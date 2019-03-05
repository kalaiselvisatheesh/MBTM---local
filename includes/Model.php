<?php
class Model extends Database
{
    /*Table Name*/
    var $adminTable              			=   "admins";
	var $deviceTokenTable					=	"devicetokens";
	var $userTable               			=   "users";
	var $relationTable						=	"usersrelationship";
	var $invitesTable 						=	"invites";
	var $tripsTable 						=	"trips";
	var $tripviolationsTable				=	"tripviolations";
	var $logTable               			=   "logs";
	var $locationtrackingTable				=	"locationtracking";
	var $notificationTrackingTable			=	"notificationtracking";
	var $oauthClientEndpointsTable  		=   "oauth_client_endpoints";
	var $oauthClientEndpointsParamsTable	=	"oauth_client_endpoints_params";
	var $oauthSessionAccessTokensTable		=	"oauth_session_access_tokens"; 
	var $oauthSessionTokenScopesTable		=	"oauth_session_token_scopes"; 
	var $oauthSessionTable					=	"oauth_sessions";
	var $oauthClientsTable 					=	"oauth_clients";
	var $oauthScopesTable 					=	"oauth_scopes";
	var $locateMenteeTable 					=	"locatementee";
	var $inappPurchaseDetailsTable 			=	"inapppurchasedetails";
	
    /*Table Name*/
	function Model()
	{
		global $globalDbManager;
		$this->dbConnect = $globalDbManager->dbConnect;
	}
}?>