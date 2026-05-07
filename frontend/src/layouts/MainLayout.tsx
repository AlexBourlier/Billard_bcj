import { Header } from "../components/layout/Header";
import { Footer } from "../components/layout/Footer";
import { Helmet } from "react-helmet-async";



export function MainLayout({ children }: { children: React.ReactNode }) {
    return (
        <>
            <Helmet>
                <title>BCJ37</title>
                <meta
                    name="description"
                    content="Bienvenue au Billard Club de Joué-lès-Tours."
                />
            </Helmet>

            <Header />
            <main className="main-content">
                <div className="app-background">
                    <div className="app-overlay" aria-hidden="true" />
                    <div className="app-shell">
                        <div className="container">
                            {children}
                        </div>
                    </div>
                </div>
            </main>
            <Footer />
        </>
    );
}