<?php

namespace App\Models\Api;

interface ZohoApiConstraints
{


    const ZOHO_API_ENDPOINT_GET_ACCOUNTS = 'https://www.zohoapis.eu/crm/v2/Accounts';
    const ZOHO_API_ENDPOINT_STORE_ACCOUNT = 'https://www.zohoapis.eu/crm/v2/Accounts';
    const ZOHO_API_ENDPOINT_STORE_DEALS = 'https://www.zohoapis.eu/crm/v2/Deals';
    const ZOHO_API_ENDPOINT_REFRESH_TOKEN = 'https://accounts.zoho.eu/oauth/v2/token';
    const ZOHO_API_ENDPOINT_INIT_TOKEN = 'https://accounts.zoho.eu/oauth/v2/init_token';


}