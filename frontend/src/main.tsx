import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './styles/index.css'
import "leaflet/dist/leaflet.css";
import App from './App.tsx'
import { HelmetProvider } from 'react-helmet-async';

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <HelmetProvider>
    <App />
    </HelmetProvider>
  </StrictMode>,
)
