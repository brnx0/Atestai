import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Index(){
    return(
        <> 
            <AuthenticatedLayout>
                <Head title='Início'></Head>

            </AuthenticatedLayout>
        </>

    );
}