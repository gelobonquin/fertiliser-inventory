
import { createApp } from 'vue'

const app = createApp(App)

import App from './components/App';

app.component('App', App)

app.mount('#app')
