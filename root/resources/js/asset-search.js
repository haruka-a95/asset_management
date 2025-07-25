import axios from "axios";

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('searchForm');
    const result = document.getElementById('result');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        axios.get(`/assets?${params}`)
            .then((res) => {
                const parser = new DOMParser();
                const html = parser.parseFromString(res.data, 'text/html');
                const newResults = html.querySelector('#result');
                result.innerHTML = newResults.innerHTML;
            }).catch((err) => {
                console.error('検索エラー', err);
            });
    });

    // リセットボタンを非同期にする
    const resetBtn = document.getElementById('resetBtn');

    if (resetBtn) {
        resetBtn.addEventListener('click', function (e){
            e.preventDefault();

            //フォームリセット
            form.querySelectorAll('input, select').forEach(el => {
                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0; // 最初の<option>に戻す
                } else {
                    el.value = ''; // テキスト、numberなどを空に
                }
            });

            axios.get(`/assets`)
                .then(res => {
                    const parser = new DOMParser();
                    const html = parser.parseFromString(res.data, 'text/html');
                    const newResults = html.querySelector('#result');
                    result.innerHTML = newResults.innerHTML;
                })
                .catch(err => {
                    console.error('リセットエラー', err);
                });
        });
    }
});