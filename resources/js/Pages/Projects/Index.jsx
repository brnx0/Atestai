import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Index({projects}){

    return (
        <>
            <AuthenticatedLayout
                header={
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Projetos 
                    </h2>
                }
            >
                <Head title='Projetos'/>

            </AuthenticatedLayout>
        </>
    );

}