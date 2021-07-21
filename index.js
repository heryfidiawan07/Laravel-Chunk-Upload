const cron = require('node-cron')
const axios = require('axios')

cron.schedule('*/5 * * * * *', function() {
    axios
    .get('http://localhost:8000/upload/1')
    .then(response => 
        console.log('response data', response.data)
    )
    console.log('Running task every second')
})