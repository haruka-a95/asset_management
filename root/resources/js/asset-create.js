import axios from 'axios';

    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('categoryId');
        const assetNumberInput = document.querySelector('input[name="asset_number"]');

        if (categorySelect && assetNumberInput) {
            categorySelect.addEventListener('change', function () {
                const categoryId = this.value;

                if (categoryId) {
                    axios.get(`/api/assets/next-number/${categoryId}`)
                        .then(response => {
                            assetNumberInput.value = response.data.asset_number;
                        })
                        .catch(error => {
                            console.error('資産番号の取得に失敗しました', error);
                            assetNumberInput.value = '';
                        });
                } else {
                    assetNumberInput.value = '';
                }
            });
        }
    });
