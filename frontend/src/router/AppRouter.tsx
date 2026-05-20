import { BrowserRouter, Route, Routes } from "react-router-dom";
import { HomePage } from "../pages/HomePage";
import { DisciplinePage } from "../pages/DisciplinePage";
import { NotFoundPage } from "../pages/NotFoundPage";
import { PostPage } from "../pages/PostPage";
import { ClubPostsPage } from "../pages/ClubPostsPage";
import { CalendarPage } from "../pages/CalendarPage";
import { ContactPage } from "../pages/ContactPage";
import { ErrorPage } from "../pages/ErrorPage";
import { CGUPage } from "../pages/CGUPage";
import { DisciplineDocumentsPage } from "../components/discipline/DisciplineDocumentsPage";
import { DisciplineRankingsPage } from "../components/discipline/DisciplineRankingsPage";
import { DisciplineCalendarPage } from "../components/discipline/DisciplineCalendarPage";
import { DisciplinePostsPage } from "../components/discipline/DisciplinePostsPage";
import { MainLayout } from "../layouts/MainLayout";
import { useEffect } from "react";
import { MentionsPage } from "../pages/MentionsLegales";
import { PolitiqueConfidentialitePage } from "../pages/PolitiqueConfidentialite";

export function AdminRedirect() {
    useEffect(() => {
        window.location.replace("http://localhost:8000/admin");
    }, []);

    return null;
}
    
export function AppRouter() {
    return (
        <BrowserRouter>
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
        </BrowserRouter>
    );
}