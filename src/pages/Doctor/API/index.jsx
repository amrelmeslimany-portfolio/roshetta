export const addClinic = (formData) => {
  return fetch('http://localhost:80/roshetta/api/doctors/add_clinic', {
    method: 'POST',
    // headers: {
    //   'content-type': 'multipart/form-data',
    // },
    body: formData,
  }).then((res) => res.json());
};

export const logOut = () => {
  const userData = JSON.parse(localStorage.getItem('userData')) ?? null;
  localStorage.clear('userData');
  return fetch(
    `http://localhost:80/roshetta/api/users/logout?Auth=bearer%20${userData.token}`
  ).then((res) => res.json());
};

// هاااااااااااام جدا لاتنسى

// const getTaxAmount = (price, taxRate) => {
//   return Promise.resolve(Math.floor((price * taxRate) / 100));
// };

// getTaxAmount(100, 12).then((taxAmount) => console.log(taxAmount));

export const viewProfile = (token) => {
  return fetch(
    `http://localhost:80/roshetta/api/users/profile?Auth=bearer%20${token}`
  ).then((res) => res.json());
};
