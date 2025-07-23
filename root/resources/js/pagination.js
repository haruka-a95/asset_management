import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    const result = document.getElementById('result');

    //ページネーションを監視
    result.addEventListener('click', function(e){
        if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
            e.preventDefault();

            const url = e.target.href;

            axios.get(url)
                .then(res => {
                    const parser = new DOMParser();
                    const html = parser.parseFromString(res.data, 'text/html');
                    const newResults = html.querySelector('#results');
                    result.innerHTML = newResults.innerHTML;
                })
                .catch(err => {
                    console.error('ページネーションエラー', err);
                });
        }
    });
})