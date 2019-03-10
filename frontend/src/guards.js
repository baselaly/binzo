import axios from 'axios';

function checkUser() {
    let token = window.$cookies.get('Utoken');
    return new Promise(resolve => {
        if (!token) {
            resolve(false);
        }
        axios
            .get("http://localhost/binzo/backend/apis/user/checkUser.php", {
                headers: {
                    Authorization: 'Bearer ' + token,
                }
            })
            .then(response => {
                let code = response.data.code;
                if (code !== 200) {
                    resolve(false);
                }
                resolve(true);
            })
            .catch(error => {
                resolve(false);
            });
    })
}

export async function UserGuard(to, from, next) {
    let user = await checkUser();
    if (to.meta.Auth) {
        !user ? next('/login') : next();
    } else if (to.meta.NotAuth) {
        user ? next('/') : next();
    }
}