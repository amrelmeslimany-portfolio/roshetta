import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { AppProvider } from './context';
import { ConfigProvider } from 'antd';
import App from './App';

const rootElement = document.getElementById('root');
const root = createRoot(rootElement);

root.render(
  <StrictMode>
    <AppProvider>
      <ConfigProvider direction="rtl">
        <App />
      </ConfigProvider>
    </AppProvider>
  </StrictMode>
);
