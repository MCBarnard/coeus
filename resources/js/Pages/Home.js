// THREE JS ##################################
import * as THREE from 'three';

const SEPARATION = 100;
const AMOUNTX = 200;
const AMOUNTY = 700;
const navbarHeight = 70;
let camera, scene, renderer;

let particles, count = 0;
let windowHalfX = window.innerWidth / 2;
let windowHalfY = (window.innerHeight  - navbarHeight) / 2;

init();
animate();

function init() {

    const canvas = document.getElementById( 'canvas' );
    camera = new THREE.PerspectiveCamera( 75, window.innerWidth / (window.innerHeight  - navbarHeight), 100, 10000 );
    camera.position.z = 3000;
    camera.position.y = 400;
    camera.position.x = -1700;

    scene = new THREE.Scene();
    scene.background = new THREE.Color( '#252b3a' );

    const numParticles = AMOUNTX * AMOUNTY;

    const positions = new Float32Array( numParticles * 3 );
    const scales = new Float32Array( numParticles );

    let i = 0, j = 0;

    for ( let ix = 0; ix < AMOUNTX; ix ++ ) {

        for ( let iy = 0; iy < AMOUNTY; iy ++ ) {

            positions[ i ] = ix * SEPARATION - ( ( AMOUNTX * SEPARATION ) / 2 ); // x
            positions[ i + 1 ] = 0; // y
            positions[ i + 2 ] = iy * SEPARATION - ( ( AMOUNTY * SEPARATION ) / 2 ); // z

            scales[ j ] = 1;

            i += 3;
            j ++;

        }

    }

    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute( 'position', new THREE.BufferAttribute( positions, 3 ) );
    geometry.setAttribute( 'scale', new THREE.BufferAttribute( scales, 1 ) );

    const material = new THREE.ShaderMaterial( {
        uniforms: {
            color: { value: new THREE.Color( 0xffffff ) },
        },
        vertexShader: document.getElementById( 'vertexshader' ).textContent,
        fragmentShader: document.getElementById( 'fragmentshader' ).textContent

    } );

    //

    particles = new THREE.Points( geometry, material );
    scene.add( particles );

    //

    renderer = new THREE.WebGLRenderer( { alpha: true, canvas, antialias: true } );
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth, (window.innerHeight  - navbarHeight) );

    canvas.style.touchAction = 'none';

    //

    window.addEventListener( 'resize', onWindowResize );

}

function onWindowResize() {

    windowHalfX = window.innerWidth / 2;
    windowHalfY = (window.innerHeight  - navbarHeight) / 2;

    camera.aspect = window.innerWidth / (window.innerHeight  - navbarHeight);
    camera.updateProjectionMatrix();

    renderer.setSize( window.innerWidth, (window.innerHeight  - navbarHeight) );

}

function animate() {

    requestAnimationFrame( animate );
    render();

}

function render() {
    camera.lookAt( scene.position );
    const positions = particles.geometry.attributes.position.array;
    const scales = particles.geometry.attributes.scale.array;

    let i = 0, j = 0;

    for ( let ix = 0; ix < AMOUNTX; ix ++ ) {

        for ( let iy = 0; iy < AMOUNTY; iy ++ ) {

            positions[ i + 1 ] = ( Math.sin( ( ix + count ) * 0.3 ) * 50 ) +
                ( Math.sin( ( iy + count ) * 0.5 ) * 50 );

            scales[ j ] = ( Math.sin( ( ix + count ) * 0.3 ) + 1 ) * 20 +
                ( Math.sin( ( iy + count ) * 0.5 ) + 1 ) * 20;

            i += 3;
            j ++;

        }

    }

    particles.geometry.attributes.position.needsUpdate = true;
    particles.geometry.attributes.scale.needsUpdate = true;

    renderer.render( scene, camera );

    count += 0.1;

}
// END THREE JS ##################################


