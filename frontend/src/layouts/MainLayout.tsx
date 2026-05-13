import { Header } from "../components/layout/Header";
import { Footer } from "../components/layout/Footer";
// import { Helmet } from "react-helmet-async";



export function MainLayout({ children }: { children: React.ReactNode }) {
    return (
        <>

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