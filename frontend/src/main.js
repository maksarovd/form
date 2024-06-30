import { createApp } from 'vue'
import { createI18n } from 'vue-i18n'
import App from './App.vue'

const i18n = createI18n({
    locale: 'ua',
    fallbackLocale: 'en',
    messages: {
        ua: {
            validation: {
                required: 'Це поле є обов\'язковим\n',
            },
            stage_0: 'Етап 0. Створити токен ZOHO',
            stage_0_client_id: 'Ід ключ клієнта',
            stage_0_client_secret: 'Секретний ключ клієнта',
            stage_0_code: 'Код',
            stage_0_redirect_uri: 'Авторизовані URI перенаправлення',
            stage_0_client_id_placeholder: 'брати iз zoho api console',
            stage_0_client_secret_placeholder: 'брати iз zoho api console',
            stage_0_code_placeholder: 'брати iз документації',
            stage_0_redirect_uri_placeholder: 'брати iз zoho api console',
            form_add: 'Додати',
            missing_token: 'Токен Zoho відсутній. Будь ласка, скористайтеся <strong> <a href="https://api-console.zoho.eu/" target="_blank">console api</a></strong>, щоб заповнити форму.',
            missing_token_step_2: 'Наступним кроком буде додавання токенів до цієї форми',
            explain_token_code_getting: 'Якщо параметри вказані вірно, перейдіть до цієї ссылки, підключіть синхронізацію з додатком і ви будете переадресовані на сторінку. Всередині URI ви знайдете [code=] параметра, який вкажіть у останньому полі',
            stage_1: 'Етап 1. Виберіть контрагента для cтворення угоди',
            stage_1_notice: 'Виберіть один із створених контрагентів zoho',
            stage_1_create_account: 'Або створiть нового контрагента zoho',
            stage_1_name: 'Ім\'я',
            stage_1_website: 'Веб-сайт',
            stage_1_phone: 'Телефон',
            stage_1_city: 'Платіжне місто',
            form_submit: 'Створити',
            stage_2: 'Етап 2. Створення нової угоди',
            stage_2_name: 'Назва угоди',
            stage_2_amount: 'Сума',
            stage_2_stage: 'Етап',
            stage_2_stage_notice: 'Виберіть один із етапів угоди',
            stage_2_date: 'Дата закриття',
        },
        en: {
            validation: {
                required: 'This field is required',
            },
            stage_0: 'Stage 0. Create ZOHO token',
            stage_0_client_id: 'Client Id',
            stage_0_client_secret: 'Client Secret',
            stage_0_code: 'Code',
            stage_0_redirect_uri: 'Authorized Redirect URIs',
            stage_0_client_id_placeholder: 'get form zoho api console',
            stage_0_client_secret_placeholder: 'get form zoho api console',
            stage_0_code_placeholder: 'get form documentation',
            stage_0_redirect_uri_placeholder: 'get form zoho api console',
            form_add: 'Add',
            missing_token: 'Zoho Token is Missing. Please use <strong> <a href="https://api-console.zoho.eu/"" target="_blank">console api</a></strong>to fix this.',
            missing_token_step_2: 'Next step will be adding tokens to this form',
            explain_token_code_getting: 'If the parameters are specified correctly, follow this link, confirm synchronization with the application, and you will be redirected to the page. Inside the URI you will find the code parameter, which you specify in the last field',
            stage_1: 'Stage 1. Select Account to make deal',
            stage_1_notice: 'Select one of created zoho accounts',
            stage_1_create_account: 'Or Create new account',
            stage_1_name: 'Name',
            stage_1_website: 'Website',
            stage_1_phone: 'Phone',
            stage_1_city: 'Billing City',
            stage_1_form_submit: 'Create',
            stage_2: 'Stage 2. Create new deal',
            stage_2_name: 'Deal Name',
            stage_2_amount: 'Amount',
            stage_2_stage: 'Stage',
            stage_2_stage_notice: 'Select one of deal stages',
            stage_2_date: 'Closing Date',
        }
    }
});


const app = createApp(App);

app.use(i18n);
app.mount('#app');