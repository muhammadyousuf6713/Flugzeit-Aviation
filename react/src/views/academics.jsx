import React, { useEffect, useState } from "react";
import { fetchAcademicPrograms } from "../api";
import { Link } from "react-router-dom";
import { FaPlus, FaEdit, FaTrash } from "react-icons/fa";
import DataTable from "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import $ from "jquery";
import "./academics.css";

export default function Academics() {
    const [academicPrograms, setAcademicPrograms] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const getAcademicPrograms = async () => {
            try {
                const data = await fetchAcademicPrograms();
                setAcademicPrograms(data.data);
            } catch (err) {
                setError("Failed to fetch academic programs");
            } finally {
                setLoading(false);
            }
        };

        getAcademicPrograms();
    }, []);

    useEffect(() => {
        if (academicPrograms.length > 0) {
            $(document).ready(function () {
                $("#dataTable").DataTable({
                    responsive: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search programs...",
                    },
                });
            });
        }
    }, [academicPrograms]);

    if (loading)
        return (
            <div className="d-flex flex-column align-items-center mt-5">
                <div className="spinner-border text-primary" role="status"></div>
                <p className="mt-2">Loading academic programs...</p>
            </div>
        );

    if (error)
        return (
            <div className="alert alert-danger text-center mt-5">
                {error}
            </div>
        );

    return (
        <main className="container mt-4">
            <div className="card shadow-sm">
                <div className="card-header d-flex justify-content-between align-items-center bg-light">
                    <h5 className="mb-0">Academic Programs</h5>
                    <div>
                        <Link
                            to="/academic-program/create"
                            className="btn btn-primary btn-sm me-2"
                        >
                            <FaPlus className="me-1" /> Add Program
                        </Link>
                        <Link
                            to="/academic-program-category/create"
                            className="btn btn-secondary btn-sm"
                        >
                            <FaPlus className="me-1" /> Add Category
                        </Link>
                    </div>
                </div>
                <div className="card-body">
                    <div className="table-responsive">
                        <table
                            id="dataTable"
                            className="table table-striped table-bordered align-middle"
                        >
                            <thead className="thead-dark bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Program Name</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Categories</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {academicPrograms.length > 0 ? (
                                    academicPrograms.map((program, index) => (
                                        <tr key={program.id}>
                                            <td>{index + 1}</td>
                                            <td>{program.name}</td>
                                            <td>{program.title || "N/A"}</td>
                                            <td>
                                                {program.description
                                                    ? program.description.split(" ").slice(0, 15).join(" ") +
                                                    "..."
                                                    : "No description available"}
                                            </td>
                                            <td>
                                                <ul className="list-unstyled">
                                                    {program.categories && program.categories.length > 0 ? (
                                                        program.categories.map((category) => (
                                                            <li key={category.id}>
                                                                <span className="badge bg-secondary">
                                                                    {category.name}
                                                                </span>
                                                            </li>
                                                        ))
                                                    ) : (
                                                        <span>No categories available</span>
                                                    )}
                                                </ul>
                                            </td>
                                            <td>
                                                <div className="d-flex align-items-center">
                                                    <Link
                                                        to={`/academic-program/edit/${program.id}`}
                                                        className="btn btn-sm btn-warning me-2"
                                                        title="Edit Program"
                                                    >
                                                        <FaEdit />
                                                    </Link>
                                                    <button
                                                        className="btn btn-sm btn-danger"
                                                        title="Delete Program"
                                                        onClick={() =>
                                                            window.confirm(
                                                                "Are you sure you want to delete this program?"
                                                            ) && handleDelete(program.id)
                                                        }
                                                    >
                                                        <FaTrash />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="text-center">
                                            <span className="text-muted">No programs available.</span>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    );
}
