import { Header } from "../components/layout/Header";
import { Footer } from "../components/layout/Footer";

export function MainLayout({ children }: { children: React.ReactNode }) {
    return (
        <div className="app-background">
            <div className="app-overlay" aria-hidden="true" />

            <div className="app-shell">
                <Header />

                <main className="main-content">
                    <div className="container">
                        {children}
                    </div>
                </main>

                <footer className="site-footer">
                    <Footer />
                </footer>
            </div>
        </div>
    );
}