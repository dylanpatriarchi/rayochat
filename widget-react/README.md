# RayoChat Widget React

Widget React per integrare RayoChat AI Customer Care nel tuo sito.

## Installazione

```bash
npm install @rayochat/widget
```

## Utilizzo

```tsx
import RayoChatWidget from '@rayochat/widget';

function App() {
  return (
    <div>
      <RayoChatWidget 
        apiKey="sk_your_api_key_here"
        apiUrl="https://yourdomain.com/api/widget"
        position="bottom-right"
        primaryColor="#FF6B35"
      />
    </div>
  );
}
```

## Props

- `apiKey` (required): La tua API key di RayoChat
- `apiUrl` (optional): URL dell'API del backend (default: https://yourdomain.com/api/widget)
- `position` (optional): Posizione del widget (`bottom-right` o `bottom-left`, default: `bottom-right`)
- `primaryColor` (optional): Colore primario del widget (default: `#FF6B35`)

## Design

Il widget utilizza un design minimalista ispirato a Stripe con:
- Colori: Bianco, Arancione (#FF6B35), Nero
- Font: Poppins
- Animazioni fluide
- Responsive design

## Licenza

MIT
