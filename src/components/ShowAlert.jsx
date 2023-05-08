import React from 'react';
import { useGlobalContext } from '../context';

const ShowAlert = ({ msg, type }) => {
  const { alert, setAlert } = useGlobalContext();
console.log('errr')
  window.scrollTo({
    top: 0,
    left: 0,
    behavior: 'smooth',
  });
  setAlert({
    msg: msg || 'حدثت مشكلة',
    show: true,
    type: type || 'error',
  });
};

export default ShowAlert;
