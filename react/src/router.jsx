import { createBrowserRouter } from 'react-router-dom';
import Academics from './views/academics.jsx';
import Createacademics from './views/Createacademics.jsx';
import Notfound from './views/Notfound.jsx';

const router = createBrowserRouter([
    {
        path: '/academic-program/list',
        element: <Academics />,
    },
    {
        path: '/academic-program/create',
        element: <Createacademics />,
    },
    // {
    //     path: '/*',
    //     element: <Notfound />
    // }
]);

export default router;
