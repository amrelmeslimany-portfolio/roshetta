import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { AppProvider } from './context'

import App from './App';

const rootElement = document.getElementById('root');
const root = createRoot(rootElement);

root.render(
  <StrictMode>
    <AppProvider>
      <App />
    </AppProvider>
  </StrictMode>
);
