import {
  useContext,
  createContext,
  useReducer,
  useEffect,
  useState,
} from 'react';
import reducer from './reducer';

// Created By Muhammad El Hassan @2022 All rights reserved

const AppContext = createContext();

const initialState = {
  loading: false,
  alert: { msg: '', show: false, type: '' },
  auth: 'No clients Found',
};

const AppProvider = ({ children }) => {
  const [state, dispatch] = useReducer(reducer, initialState);
  
  const setAuthUser = (auth) => {
    dispatch({ type: 'SET_AUTH_USER', payload: auth });
  };

  return (
    <AppContext.Provider
      value={{
        ...state,
        setAuthUser,
      }}
    >
      {children}
    </AppContext.Provider>
  );
};

const useGlobalContext = () => {
  return useContext(AppContext);
};

export { AppProvider, useGlobalContext };


