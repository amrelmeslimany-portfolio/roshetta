import {
  useContext,
  createContext,
  useReducer,
  useEffect,
  useState,
} from 'react';
import reducer from './reducer';

const AppContext = createContext();

const initialState = {
  loading: false,
  alert: { msg: '', show: false, type: '' },
  auth: 400,
};

const AppProvider = ({ children }) => {
  const [state, dispatch] = useReducer(reducer, initialState);

  const setAuthUser = (auth) => {
    dispatch({ type: 'SET_AUTH_USER', payload: auth });
  };
  const setAlert = ({ msg, show, type }) => {
    dispatch({ type: 'SET_ALERT_MESSAGE', payload: { msg, show, type } });
  };

  return (
    <AppContext.Provider
      value={{
        ...state,
        setAuthUser,
        setAlert,
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
