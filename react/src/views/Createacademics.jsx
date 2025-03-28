import React, { useEffect, useState } from "react";
import 'froala-editor/css/froala_editor.pkgd.min.css';
import $ from "jquery";
import FroalaEditor from "froala-editor";

const Createacademics = () => {
    const [errors, setErrors] = useState([]);
    const [formData, setFormData] = useState({
        name: "",
        title: "",
        description: "",
    });

    useEffect(() => {
        // Initialize Froala Editor
        const editor = new FroalaEditor(".description", {
            videoUploadURL: "/UploadFiles",
            videoUploadParams: {
                id: "my_editor",
            },
            events: {
                contentChanged: function () {
                    // Sync Froala content with React state
                    setFormData((prevState) => ({
                        ...prevState,
                        description: this.html.get(),
                    }));
                },
            },
        });

        // Initialize DataTable
        $("#dataTable").DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records",
            },
        });
    }, []);

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        // Handle form validation
        if (!formData.name || !formData.title || !formData.description.trim()) {
            setErrors(["All fields are required"]);
            return;
        }

        // Clear errors and submit data
        setErrors([]);
        try {
            // Send a POST request to the Laravel API
            const response = await fetch("http://localhost/backend-aku/public/api/api-academic-program/store", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify({
                    name: formData.name,
                    title: formData.title,
                    description: formData.description,
                }),
            });

            // Parse the response
            const data = await response.json();

            if (response.ok) {
                // Show a success message or handle success
                alert("Academic program created successfully!");
                setFormData({ name: "", title: "", description: "" }); // Clear form data
            } else {
                // Handle errors from the server
                setErrors(data.errors || ["An error occurred while submitting the form"]);
            }
        } catch (error) {
            // Handle network or other errors
            setErrors(["Failed to connect to the server. Please try again later."]);
        }
    };
    return (
        <main className="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
            <div className="container-fluid py-4">
                <div className="row">
                    <div className="col-12">
                        <div className="card mb-4">
                            <div className="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                                <h6>Academic Programme Table</h6>
                                <a href="/academic-program/list" className="btn">
                                    List
                                </a>
                            </div>
                            <div className="card-body px-0 pt-0 pb-2">
                                <div className="table-responsive p-3">
                                    <div className="row">
                                        <div className="col-md-12 col-lg-12 col-xl-12">
                                            <div className="card card-body pd-40">
                                                <h4 className="card-title mg-b-20">Add Academic Programme</h4>
                                                {errors.length > 0 && (
                                                    <div className="p-1">
                                                        {errors.map((error, index) => (
                                                            <div
                                                                key={index}
                                                                className="alert alert-warning alert-danger fade show"
                                                                role="alert"
                                                            >
                                                                {error}
                                                                <button
                                                                    type="button"
                                                                    className="close"
                                                                    data-dismiss="alert"
                                                                    aria-label="Close"
                                                                >
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        ))}
                                                    </div>
                                                )}
                                                <form onSubmit={handleSubmit}>
                                                    <div>
                                                        <div className="row row-sm mg-b-20">
                                                            <div className="col-md-6">
                                                                <div className="form-group">
                                                                    <label className="az-content-label tx-11 tx-medium tx-gray-600">
                                                                        Name <span className="text-danger">*</span>
                                                                    </label>
                                                                    <input
                                                                        type="text"
                                                                        name="name"
                                                                        className="form-control"
                                                                        required
                                                                        value={formData.name}
                                                                        onChange={handleInputChange}
                                                                    />
                                                                </div>
                                                            </div>
                                                            <div className="col-md-6">
                                                                <div className="form-group">
                                                                    <label className="az-content-label tx-11 tx-medium tx-gray-600">
                                                                        Title <span className="text-danger">*</span>
                                                                    </label>
                                                                    <input
                                                                        type="text"
                                                                        name="title"
                                                                        className="form-control"
                                                                        required
                                                                        value={formData.title}
                                                                        onChange={handleInputChange}
                                                                    />
                                                                </div>
                                                            </div>
                                                            <div className="form-group col-md-12">
                                                                <label htmlFor="description">Description</label>
                                                                <textarea
                                                                    name="description"
                                                                    id="description"
                                                                    className="form-control description"
                                                                    rows="4"
                                                                    value={formData.description}
                                                                    onChange={handleInputChange}
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <a
                                                        type="button"
                                                        href="/academic-program/list"
                                                        className="btn btn-danger btn-block mt-2"
                                                    >
                                                        Cancel
                                                    </a>
                                                    <button
                                                        type="submit"
                                                        className="btn btn-primary btn-block mt-2"
                                                        style={{ float: "right" }}
                                                    >
                                                        Submit
                                                    </button>
                                                </form>
                                            </div>
                                            {/* card */}
                                        </div>
                                        {/* col */}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    );
};

export default Createacademics;
