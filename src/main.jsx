import React from 'react'
import ReactDOM from 'react-dom/client'
import { AppProvider } from './context';
import { ConfigProvider } from 'antd';
import App from './App';
import './index.css'

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <AppProvider>
      <ConfigProvider direction="rtl">
          <App />
      </ConfigProvider>
    </AppProvider>
  </React.StrictMode>
);
