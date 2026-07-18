import { lazy, Suspense, useEffect } from "react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { MainLayout } from "../layouts/MainLayout";
import { CookieBanner } from "../components/cookies/CookieBanner";
import { MatomoTracker } from "../components/analytics/MatomoTracker";
import { HomePage } from "../pages/HomePage";
// ErrorPage est deja inclus dans le bundle initial (importe par plusieurs pages
// comme composant de repli) : un chargement differe serait sans effet.
import { ErrorPage } from "../pages/ErrorPage";

// La page d'accueil est chargee immediatement (import statique) : c'est la
// route la plus visitee et l'entree principale, un chargement differe y
// ajouterait un aller-retour reseau avant le premier rendu (au detriment du
// FCP/LCP). Les autres pages restent en chargement differe : chacune devient
// un fichier JS separe, telecharge uniquement lorsqu'on visite sa route.
const DisciplinePage = lazy(() => import("../pages/DisciplinePage").then((m) => ({ default: m.DisciplinePage })));
const NotFoundPage = lazy(() => import("../pages/NotFoundPage").then((m) => ({ default: m.NotFoundPage })));
const PostPage = lazy(() => import("../pages/PostPage").then((m) => ({ default: m.PostPage })));
const ClubPostsPage = lazy(() => import("../pages/ClubPostsPage").then((m) => ({ default: m.ClubPostsPage })));
const CalendarPage = lazy(() => import("../pages/CalendarPage").then((m) => ({ default: m.CalendarPage })));
const ContactPage = lazy(() => import("../pages/ContactPage").then((m) => ({ default: m.ContactPage })));
const CGUPage = lazy(() => import("../pages/CGUPage").then((m) => ({ default: m.CGUPage })));
const MentionsPage = lazy(() => import("../pages/MentionsLegales").then((m) => ({ default: m.MentionsPage })));
const PolitiqueConfidentialitePage = lazy(() => import("../pages/PolitiqueConfidentialite").then((m) => ({ default: m.PolitiqueConfidentialitePage })));
const DisciplineDocumentsPage = lazy(() => import("../components/discipline/DisciplineDocumentsPage").then((m) => ({ default: m.DisciplineDocumentsPage })));
const DisciplineRankingsPage = lazy(() => import("../components/discipline/DisciplineRankingsPage").then((m) => ({ default: m.DisciplineRankingsPage })));
const DisciplineCalendarPage = lazy(() => import("../components/discipline/DisciplineCalendarPage").then((m) => ({ default: m.DisciplineCalendarPage })));
const DisciplinePostsPage = lazy(() => import("../components/discipline/DisciplinePostsPage").then((m) => ({ default: m.DisciplinePostsPage })));

export function AdminRedirect() {
    useEffect(() => {
        window.location.replace("https://api.test.alexandrebourlier.fr/admin");
    }, []);

    return null;
}
    
export function AppRouter() {
    return (
        <BrowserRouter>
            <MatomoTracker />
            <Suspense fallback={<div className="route-loading" aria-busy="true" aria-label="Chargement" />}>
            <Routes>
                <Route
                    path="/"
                    element={
                        <MainLayout>
                            <HomePage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/disciplines/:discipline"
                    element={
                        <MainLayout>
                            <DisciplinePage />
                        </MainLayout>
                    }
                >
                    <Route index element={<DisciplinePostsPage />} />
                    <Route path="articles" element={<DisciplinePostsPage />} />
                    <Route path="calendrier" element={<DisciplineCalendarPage />} />
                    <Route path="classements" element={<DisciplineRankingsPage />} />
                    <Route path="documents" element={<DisciplineDocumentsPage />} />
                </Route>

                <Route
                    path="/posts/:slug"
                    element={
                        <MainLayout>
                            <PostPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/club"
                    element={
                        <MainLayout>
                            <ClubPostsPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/club/annee/:period"
                    element={
                        <MainLayout>
                            <ClubPostsPage />
                        </MainLayout>
                    }
                />

                <Route 
                    path="/calendrier"
                    element={
                        <MainLayout>
                            <CalendarPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/contact"
                    element={
                        <MainLayout>
                            <ContactPage />
                        </MainLayout>
                    }
                />

                <Route 
                    path="/erreur"
                    element={
                        <MainLayout>
                            <ErrorPage />
                        </MainLayout>
                    }
                />
                <Route
                    path="/404"
                    element={
                        <MainLayout>
                            <NotFoundPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="*"
                    element={
                        <MainLayout>
                            <NotFoundPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/cgu"
                    element={
                        <MainLayout>
                            <CGUPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/mentions-legales"
                    element={
                        <MainLayout>
                            <MentionsPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/politique-confidentialite"
                    element={
                        <MainLayout>
                            <PolitiqueConfidentialitePage />
                        </MainLayout>
                    }
                />

                <Route 
                    path="/admin"
                    element={<AdminRedirect />}
                />
                <Route 
                    path="/login"
                    element={<AdminRedirect />}
                />
            </Routes>
            </Suspense>
            <CookieBanner />
        </BrowserRouter>
    );
}