import { useEffect, useState } from "react";
import React from 'react';
import ReactDOM from 'react-dom/client';

function Dogs() {
    const [apiResponse, setApiResponse] = useState([]);
    const [userToken, setUserToken] = useState([]);
    const [userId, setUserId] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            const root = document.getElementById('dogs');
            const user = JSON.parse(root.getAttribute('data-user'));
            const response = await fetch('/api/listdogs', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + user.token,
                },
            });
            const data = await response.json();
            setApiResponse(data);
        };
        fetchData();
    }, []);


    return (
        <table className={'table table-striped'}>
            <thead>
            <tr>
                <th colSpan={3}>Dogs count: {apiResponse?.data?.dogs?.length}</th>
            </tr>
                <tr>
                    <th>Name</th>
                    <th>Breed</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tbody>
                {apiResponse?.data?.dogs?.map(dog => (
                    <tr key={dog.id}>
                        <td>{dog.name}</td>
                        <td>{dog.breed}</td>
                        <td>{dog.age}</td>
                    </tr>
                ))}
            </tbody>
        </table>
    );
}
export default Dogs;
if (document.getElementById('dogs')) {
    console.log("dogs");
    const Index = ReactDOM.createRoot(document.getElementById("dogs"));

    Index.render(
        <React.StrictMode>
            <Dogs/>
        </React.StrictMode>
    )
}