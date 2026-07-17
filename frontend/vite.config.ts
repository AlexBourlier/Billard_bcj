import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  build: {
    rollupOptions: {
      output: {
        // Isole les grosses dependances tierces dans des chunks separes afin
        // de reduire la taille du bundle principal (et permettre leur mise en
        // cache navigateur independamment du code applicatif).
        manualChunks(id: string) {
          if (!id.includes('node_modules')) return
          if (id.includes('leaflet')) return 'leaflet'
          if (
            id.includes('react-router') ||
            id.includes('react-dom') ||
            /node_modules[\\/]react[\\/]/.test(id)
          ) {
            return 'react'
          }
        },
      },
    },
  },
})
