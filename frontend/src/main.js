import { createApp } from 'vue'
import { createI18n } from 'vue-i18n'
import App from './App.vue'

const i18n = createI18n({
    locale: 'ua',
    fallbackLocale: 'en',
    messages: {
        ua: {
            missing_token: 'Токен Zoho відсутній. Будь ласка, скористайтеся <strong> <a href="https://github.com/maksarovd/form" target="_blank">документацією</a></strong>, щоб виправити це.',
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
            missing_token: 'Zoho Token is Missing. Please use <strong> <a href="https://github.com/maksarovd/form" target="_blank">visit a documentation</a></strong> endpoint to fix this.',
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