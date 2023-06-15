import { createContext, useEffect, useReducer } from "react";
import { AUTH_STATES, INITIAL_AUTH_STATE, authReducer } from "./reducer";

export const AuthContext = createContext(INITIAL_AUTH_STATE);

const AuthProvider = ({ children }) => {
  const [state, dispatch] = useReducer(authReducer, INITIAL_AUTH_STATE);

  const loginAction = (user) => dispatch({ type: AUTH_STATES.LOGIN, user });
  const logoutAction = () => dispatch({ type: AUTH_STATES.LOGOUT });

  return (
    //  NOTE this code {...state} means extract which within in this state
    <AuthContext.Provider value={{ loginAction, logoutAction, ...state }}>
      {children}
    </AuthContext.Provider>
  );
};

export default AuthProvider;
